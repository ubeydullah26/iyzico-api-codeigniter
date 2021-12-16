<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Iyzico
{
    
    protected $options;
    protected $request;
    protected $basketItems;

    public function __construct()
    {
        require_once(APPPATH. 'libraries/iyzipay-php/IyzipayBootstrap.php');

        IyzipayBootstrap::init();
        $this->options = new \Iyzipay\Options();
        $this->options->setApiKey("sandbox-FpaEtW2DXR1C2UfZRGRFRO9hiqzcYceF");
        $this->options->setSecretKey("sandbox-wz1WFzx26V3Rk1gY9qSGI7sy5BBblatz");
        $this->options->setBaseUrl("https://sandbox-api.iyzipay.com");

        $this->basketItems = array();
    }

    public function setForm($params = array())
    {
        $this->request = new \Iyzipay\Request\CreateCheckoutFormInitializeRequest();
        $this->request->setLocale(\Iyzipay\Model\Locale::TR);
        $this->request->setConversationId($params['conversationID']);
        $this->request->setPrice($params['price']);
        $this->request->setPaidPrice($params['paidPrice']);
        $this->request->setCurrency(\Iyzipay\Model\Currency::TL);
        $this->request->setBasketId($params['basketID']);
        $this->request->setPaymentGroup(\Iyzipay\Model\PaymentGroup::PRODUCT);
        $this->request->setCallbackUrl(base_url("payment/callback"));
        $this->request->setEnabledInstallments(array(2, 3, 6, 9));
        return $this;
    }

    public function setBuyer($params = array())
    {
        $buyer = new \Iyzipay\Model\Buyer();
        $buyer->setId($params['user_id']);
        $buyer->setName($params['name']);
        $buyer->setSurname($params['surname']);
        $buyer->setGsmNumber($params['phone']);
        $buyer->setEmail($params['email']);
        $buyer->setIdentityNumber($params['identity']);
        $buyer->setRegistrationAddress($params['address']);
        $buyer->setIp($params['ip']);
        $buyer->setCity($params['city']);
        $buyer->setCountry($params['country']);
        $this->request->setBuyer($buyer);
        return $this;
    }

    public function setShipping($params = array())
    {
        $shippingAddress = new \Iyzipay\Model\Address();
        $shippingAddress->setContactName($params['name']);
        $shippingAddress->setCity($params['city']);
        $shippingAddress->setCountry($params['country']);
        $shippingAddress->setAddress($params['address']);
        $this->request->setShippingAddress($shippingAddress);
        return $this;
    }
    
    public function setBilling($params = array())
    {
        $billingAddress = new \Iyzipay\Model\Address();
        $billingAddress->setContactName($params['name']);
        $billingAddress->setCity($params['city']);
        $billingAddress->setCountry($params['country']);
        $billingAddress->setAddress($params['address']);
        $this->request->setBillingAddress($billingAddress);
        return $this;
    }

    public function setItems($items = array())
    {
        foreach($items as $key => $value)
        {
            $basketItem = new \Iyzipay\Model\BasketItem();
            $basketItem->setId($value['id']);
            $basketItem->setName($value['name']);
            $basketItem->setCategory1($value['category']);
            $basketItem->setItemType(\Iyzipay\Model\BasketItemType::PHYSICAL);
            $basketItem->setPrice($value['price']);

            array_push($this->basketItems, $basketItem);
        }
        $this->request->setBasketItems($this->basketItems);
        return $this;
    }

    public function paymentForm()
    {
        $form = $checkoutFormInitialize = \Iyzipay\Model\CheckoutFormInitialize::create($this->request, $this->options);
        return $form;
    }

    public function callBackForm($token, $conversationID)
    {
        $request = new \Iyzipay\Request\RetrieveCheckoutFormRequest();
        $request->setLocale(\Iyzipay\Model\Locale::TR);
        $request->setConversationId($conversationID);
        $request->setToken($token);

        # make request
        $checkoutForm = \Iyzipay\Model\CheckoutForm::retrieve($request, $this->options);

        # print result
        return $checkoutForm;
    }
}