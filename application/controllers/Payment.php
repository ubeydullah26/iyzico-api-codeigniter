<?php

class Payment extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library("iyzico");
    }

    public function index()
    {
        $iyzico = new Iyzico();
        $payment = $iyzico->setForm([
            "conversationID" => "123456789",
            "price" => 120.0,
            "paidPrice" => 126.0,
            "basketID" => "SPT101"
        ])
        ->setBuyer([
            "user_id" => 1001,
            "name" => "Ubeydullah",
            "surname" => "Yılmazer",
            "phone" => "5428426345",
            "email" => "deneme@deneme.com",
            "identity" => "11111111110",
            "address" => "Gazi Mahallesi Ankara",
            "ip" => "198.168.1.145",
            "city" => "Ankara",
            "country" => "Türkiye"
        ])
        ->setShipping([
            "name" => "Ubeydullah Yılmazer",
            "city" => "Ankara",
            "country" => "Türkiye",
            "address" => "Gazi Mahallesi Ankara"
        ])
        ->setBilling([
            "name" => "Ubeydullah Yılmazer",
            "city" => "Ankara",
            "country" => "Türkiye",
            "address" => "Gazi Mahallesi Ankara"
        ])
        ->setItems([
            [
                "id" => 201,
                "name" => "Kırmızı Kazak",
                "category" => "Erkek Giyim",
                "price" => 40.0
            ],
            [
                "id" => 202,
                "name" => "Mavi Kazak",
                "category" => "Erkek Giyim",
                "price" => 40.0
            ],
            [
                "id" => 203,
                "name" => "Pembe Ayakkabı",
                "category" => "Kadın Ayakkabı",
                "price" => 40.0
            ]
        ])
        ->paymentForm();

        $viewData = [
            "paymentContent" => $payment->getCheckoutFormContent(),
            "paymentStatus" => $payment->getStatus()
        ];

        $this->load->view("payment_form", $viewData);
    }

    public function callback()
    {
        $token = $_REQUEST['token'];
        $conversationID = "123456789";
        $iyzico = new Iyzico();
        $response = $iyzico->callBackForm($token, $conversationID);

        $viewData = [
            "paymentStatus" => $response->getPaymentStatus(),
        ];

        $this->load->view("payment_status", $viewData);
    }
}