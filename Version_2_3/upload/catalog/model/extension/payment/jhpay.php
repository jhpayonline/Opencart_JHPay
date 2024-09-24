<?php

class ModelExtensionPaymentJhpay extends Model
{
    public function getMethod($address, $total)
    {
        $this->load->language('extension/payment/jhpay');

        $method_data = array(
            'code' => 'jhpay',
            'title' => $this->language->get('text_title'),
            'terms' => '',
            'sort_order' => $this->config->get('payment_jhpay_sort_order')
        );

        return $method_data;
    }
}
