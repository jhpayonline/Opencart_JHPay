<?php

class ControllerExtensionPaymentJhpay extends Controller
{
    private $error = [];

    public function index()
    {
        $this->load->language('extension/payment/jhpay');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('payment_jhpay', $this->request->post);
            $this->session->data['success'] = $this->language->get('text_success');
            $this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true));
        }

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['token'])) {
            $data['error_token'] = $this->error['token'];
        } else {
            $data['error_token'] = '';
        }

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true)
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/payment/jhpay', 'user_token=' . $this->session->data['user_token'], true)
        ];

        $data['action'] = $this->url->link('extension/payment/jhpay', 'user_token=' . $this->session->data['user_token'], true);

        $data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true);

        if (isset($this->request->post['payment_jhpay_token'])) {
            $data['payment_jhpay_token'] = $this->request->post['payment_jhpay_token'];
        } else {
            $data['payment_jhpay_token'] = $this->config->get('payment_jhpay_token');
        }

        if (isset($this->request->post['payment_jhpay_status'])) {
            $data['payment_jhpay_status'] = $this->request->post['payment_jhpay_status'];
        } else {
            $data['payment_jhpay_status'] = $this->config->get('payment_jhpay_status');
        }

        if (isset($this->request->post['payment_jhpay_currency'])) {
            $data['payment_jhpay_currency'] = $this->request->post['payment_jhpay_currency'];
        } else {
            $data['payment_jhpay_currency'] = $this->config->get('payment_jhpay_currency');
        }

        if (isset($this->request->post['payment_jhpay_order_status_id'])) {
            $data['payment_jhpay_order_status_id'] = $this->request->post['payment_jhpay_order_status_id'];
        } else {
            $data['payment_jhpay_order_status_id'] = $this->config->get('payment_jhpay_order_status_id');
        }

        $this->load->model('localisation/order_status');
        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $data['currencies'] = [
            'AED' => 784,
            'AFN' => 971,
            'AMD' => 051,
            'AUD' => 036,
            'AZN' => 944,
            'CAD' => 124,
            'CNY' => 156,
            'EUR' => 978,
            'GBP' => 826,
            'GEL' => 981,
            'INR' => 356,
            'IRR' => 364,
            'KGS' => 417,
            'KWD' => 414,
            'KZT' => 398,
            'MDL' => 498,
            'MYR' => 458,
            'NOK' => 578,
            'PKR' => 586,
            'PLN' => 985,
            'RUB' => 643,
            'SAR' => 682,
            'SGD' => 702,
            'TJS' => 972,
            'TRY' => 949,
            'UAH' => 980,
            'USD' => 840,
            'UZS' => 860
        ];

        $this->response->setOutput($this->load->view('extension/payment/jhpay', $data));
    }

    protected function validate()
    {
        if (!$this->user->hasPermission('modify', 'extension/payment/jhpay')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->request->post['payment_jhpay_token']) {
            $this->error['token'] = $this->language->get('error_token');
        }

        return !$this->error;
    }
}
