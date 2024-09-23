<?php

class ControllerExtensionPaymentJhpay extends Controller
{
    public function index()
    {
        $data['button_confirm'] = $this->language->get('button_confirm');
        $data['action'] = $this->url->link('extension/payment/jhpay/confirm');

        return $this->load->view('extension/payment/jhpay', $data);
    }

    public function confirm()
    {
        $this->load->model('checkout/order');

        if (!isset($this->session->data['order_id'])) {
            return false;
        }

        $order_id = $this->session->data['order_id'];
        $order_info = $this->model_checkout_order->getOrder($order_id);

        $token = $this->config->get('payment_jhpay_token');
        $amount = number_format($order_info['total'], 2, '.', '');
        $unique_order_id = $order_info['order_id'] . '-' . time();

        $data = [
            'amount' => $amount,
            'orderNumber' => $unique_order_id,
            'currency' => $this->config->get('payment_jhpay_currency'),
            'description' => $order_info['order_id'],
            'name' => implode(' ', [$order_info['firstname'], $order_info['lastname']]),
            'email' => $order_info['email'],
        ];

        $ch = curl_init('https://pay.jhpay.online/api/pay/order/create');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "API-TOKEN: $token",
        ]);

        $result = json_decode(curl_exec($ch), true);
        curl_close($ch);

        if (is_array($result) && isset($result['formUrl'])) {
            $this->response->redirect($result['formUrl']);
        }

        $this->session->data['error'] = $result['message'] ?? 'Error creating payment';
        $this->response->redirect($this->url->link('checkout/checkout', '', true));
    }

    public function callback()
    {
        $post = $this->request->post;

        if (empty($post)) {
            $post = json_decode(file_get_contents('php://input'), true);
        }

        $token = $this->config->get('payment_jhpay_token');
        $sign = $_SERVER['HTTP_SIGNATURE'];
        $sign2 = hash_hmac('sha256', $post['id'] . '|' . $post['createdDateTime'] . '|' . $post['amount'], $token);

        if ($sign === $sign2) {
            $order_id = explode('-', $post['id'])[0];
            $this->load->model('checkout/order');
            $this->model_checkout_order->addOrderHistory($order_id, $this->config->get('config_order_status_id'));
        }
    }

}
