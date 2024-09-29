<?php

class ControllerExtensionPaymentJhpay extends Controller
{
    private $error = array();

    public function index()
    {
        $this->load->language('extension/payment/jhpay');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('jhpay', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true));
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_edit'] = $this->language->get('text_edit');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_all_zones'] = $this->language->get('text_all_zones');
        $data['text_pay'] = $this->language->get('text_pay');
        $data['text_card'] = $this->language->get('text_card');

        $data['entry_token'] = $this->language->get('entry_token');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_currency'] = $this->language->get('entry_currency');
        $data['entry_order_status'] = $this->language->get('entry_order_status');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');

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

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/payment/jhpay', 'token=' . $this->session->data['token'], true)
        );

        $data['action'] = $this->url->link('extension/payment/jhpay', 'token=' . $this->session->data['token'], true);

        $data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true);

        if (isset($this->request->post['jhpay_token'])) {
            $data['jhpay_token'] = $this->request->post['jhpay_token'];
        } else {
            $data['jhpay_token'] = $this->config->get('jhpay_token');
        }

        if (isset($this->request->post['jhpay_status'])) {
            $data['jhpay_status'] = $this->request->post['jhpay_status'];
        } else {
            $data['jhpay_status'] = $this->config->get('jhpay_status');
        }

        if (isset($this->request->post['jhpay_currency'])) {
            $data['jhpay_currency'] = $this->request->post['jhpay_currency'];
        } else {
            $data['jhpay_currency'] = $this->config->get('jhpay_currency');
        }

        if (isset($this->request->post['jhpay_order_status_id'])) {
            $data['jhpay_order_status_id'] = $this->request->post['jhpay_order_status_id'];
        } else {
            $data['jhpay_order_status_id'] = $this->config->get('jhpay_order_status_id');
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

        if (!$this->request->post['jhpay_token']) {
            $this->error['token'] = $this->language->get('error_token');
        }

        return !$this->error;
    }
}
