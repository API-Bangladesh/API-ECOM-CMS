<?php

use Illuminate\Support\Facades\Session;
use Modules\Order\Entities\Order;

define('LOGO_PATH', 'logo/');
define('USER_AVATAR_PATH', 'user/');
define('EMPLOYEE_IMAGE_PATH', 'employee/');
define('BRAND_IMAGE_PATH', 'brand/');
define('CONTENT_CATEGORY_IMAGE_PATH', 'content-category/');
define('Combo_IMAGE_PATH', 'combo-image/');
define('ComboCategory_IMAGE_PATH', 'combo-category-image/');
define('COMBO_lIFESTYLE_IMAGE_PATH', 'combo-life-style/');
define('INVENTORY_SINGLE_IMAGE_PATH', 'inventory-image/');
define('PaymentMethod_IMAGE_PATH', 'payment-method/');
define('CONTENT_MODULE_IMAGE_PATH', 'content-module/');
define('CITEM_IMAGE_PATH', 'content-item/');
define('CITEM_BANNER_IMAGE_PATH', 'content-banner-item/');
define('CATEGORY_IMAGE_PATH', 'category-image/');
define('SUB_CATEGORY_IMAGE_PATH', 'sub-category-image/');
define('PRODUCT_IMAGE_PATH', 'product-image/');
define('PRODUCT_VIDEO_PATH', 'product-video/');
define('PRODUCT_MULTI_IMAGE_PATH', 'product-multi-image/');
define('INVENTORY_MULTI_IMAGE_PATH', 'inventory-multi-image/');
define('PRODUCT_LIFESTYLE_MULTI_IMAGE_PATH', 'product-lifestyle-multi-image/');
define('PRODUCT_BROCHURE', 'product-brochure/');
define('PICTURE_IMAGE_PATH', 'picture/');
define('VIDEO_PATH', 'video/');
define('DOCUMENT_IMAGE_PATH', 'document-image/');
define('BLOG_IMAGE_PATH', 'blog/');
define('PURCHASE_DOCUMENT_PATH', 'purchase-document/');
define('SALE_DOCUMENT_PATH', 'sale-document/');
define('DATE_FORMAT', date('d M, Y',));
define('GENDER', ['1' => 'Male', '2' => 'Female']);
define('TAX_METHOD', ['1' => 'Exclusive', '2' => 'Inclusive']);
define('STATUS', ['1' => 'Active', '2' => 'Inactive']);
define('MODULE_FIELD_STATUS', ['1' => 'Active', '2' => 'Inactive']);
define('PURCHASE_STATUS', ['1' => 'Received', '2' => 'Partial', '3' => 'Pending', '4' => 'Ordered']);
define('SALE_STATUS', ['1' => 'Completed', '2' => 'Pending']);
define('PAYMENT_METHOD', ['1' => 'Cash', '2' => 'Cheque', '3' => 'Mobile']);
define('PAYROLL_PAYMENT_METHOD', ['1' => 'Cash', '2' => 'Bank', '3' => 'Mobile']);
define('PURCHASE_STATUS_LABEL',
    ['1' => '<span class="badge badge-success">Received</span>',
        '2' => '<span class="badge badge-warning">Partial</span>',
        '3' => '<span class="badge badge-danger">Pending</span>',
        '4' => '<span class="badge badge-info">Ordered</span>',
    ]);
define('SALE_STATUS_LABEL',
    ['1' => '<span class="badge badge-success">Completed</span>',
        '2' => '<span class="badge badge-danger">Pending</span>',
    ]);
define('ATTENDANCE_STATUS_LABEL',
    ['1' => '<span class="badge badge-success">Present</span>',
        '2' => '<span class="badge badge-danger">Late</span>',
    ]);
define('PAYMENT_STATUS', ['1' => 'Paid', '2' => 'Due']);
define('PAYMENT_STATUS_LABEL',
    ['1' => '<span class="badge badge-success">Paid</span>',
        '2' => '<span class="badge badge-danger">Due</span>']);
define('SALE_PAYMENT_STATUS_LABEL',
    ['1' => '<span class="badge badge-success">Paid</span>',
        '2' => '<span class="badge badge-info">Partial</span>',
        '3' => '<span class="badge badge-danger">Due</span>',
    ]);

define('DELETABLE', ['1' => 'No', '2' => 'Yes']);
define('STATUS_LABEL',
    ['1' => '<span class="badge badge-success">Active</span>',
        '2' => '<span class="badge badge-danger">Inactive</span>']);

define('REVIEW_LABEL',
['1' => '<span class="text-success">1*</span>',
'2' => '<span class="text-success">2*</span>',
'3' => '<span class="text-success">3*</span>',
'4' => '<span class="text-success">4*</span>',
'5' => '<span class="text-success">5*</span>']);


define('MODULE_FIELD_STATUS_LABEL',
    ['1' => '<span class="badge badge-success">Yes</span>',
        '2' => '<span class="badge badge-danger">No</span>']);

define('MAIL_MAILER', ['smtp', 'sendmal', 'mail']);
define('MAIL_ENCRYPTION', ['none' => 'null', 'tls' => 'tls', 'ssl' => 'ssl']);
define('BARCODE_SYMBOLOGY', [
    'C128' => 'Code 128',
    'C39' => 'Code 39',
    'UPCA' => 'UPC-A',
    'UPCE' => 'UPC-E',
    'EAN8' => 'EAN-8',
    'EAN13' => 'EAN-13',
]);

if (!function_exists('permission')) {
    function permission(string $value)
    {
        if (collect(\Illuminate\Support\Facades\Session::get('permission'))->contains($value)) {
            return true;
        }
        return false;
    }
}

if (!function_exists('action_button')) {
    function action_button($action)
    {
        return '<div class="dropdown">
        <button class="btn btn-secondary dropdown-toggle d-flex align-items-center" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-th-list text-white"></i>
        </button>
        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
        ' . $action . '
        </div>
      </div>';
    }
}
if (!function_exists('table_checkbox')) {
    function table_checkbox($id)
    {
        return '<div class="custom-control custom-checkbox">
            <input type="checkbox" value="' . $id . '"
            class="custom-control-input select_data" onchange="select_single_item(' . $id . ')" id="checkbox' . $id . '">
            <label class="custom-control-label" for="checkbox' . $id . '"></label>
        </div>';
    }
}
if (!function_exists('change_status')) {
    function change_status(int $id, int $status, string $name = null)
    {
        return $status == 1 ? '<span class="badge badge-success change_status" data-id="' . $id . '" data-name="' . $name . '" data-status="2" style="cursor:pointer;">Active</span>' :
            '<span class="badge badge-danger change_status" data-id="' . $id . '" data-name="' . $name . '" data-status="1" style="cursor:pointer;">Inactive</span>';
    }
}
if (!function_exists('change_payment_status')) {
    function change_payment_status(int $id, int $status, string $name = null)
    {
        return $status == 1 ? '<span class="badge badge-success change_payment_status" data-id="' . $id . '" data-name="' . $name . '" data-status="2" style="cursor:pointer;">PAID</span>' :
            '<span class="badge badge-danger change_payment_status" data-id="' . $id . '" data-name="' . $name . '" data-status="1" style="cursor:pointer;">UNPAID</span>';
    }
}
if (!function_exists('module_field_change_status')) {
    function module_field_change_status(int $id, int $status, string $name = null)
    {
        return $status == 1 ? '<span class="badge badge-success module_field_change_status" data-id="' . $id . '" data-name="' . $name . '" data-status="2" style="cursor:pointer;">Active</span>' :
            '<span class="badge badge-danger module_field_change_status" data-id="' . $id . '" data-name="' . $name . '" data-status="1" style="cursor:pointer;">Inactive</span>';
    }
}

if (!function_exists('table_image')) {
    function table_image($image = null, $path = null, string $name = null)
    {
        return $image ? "<img src='storage/" . $path . $image . "' alt='" . $name . "' style='width:50px;'/>"
            : "<img src='images/default.svg' alt='Default Image' style='width:50px;'/>";
    }
}
if (!function_exists('table_file')) {
    function table_file($document_file = null, $path = null, string $name = null)
    {
        return $document_file ? "<a href='storage/" . $path . $document_file . "' target='_blank'>{$document_file}</a>"
            : "";
    }
}

/**
 * @return array[]
 */
function get_order_statuses()
{
    return [
        [
            'id' => Order::ORDER_STATUS_PENDING,
            'text' => "Pending"
        ],
        [
            'id' => Order::ORDER_STATUS_PROCESSING,
            'text' => "Processing"
        ],
        [
            'id' => Order::ORDER_STATUS_SHIPPED,
            'text' => "Shipped"
        ],
        [
            'id' => Order::ORDER_STATUS_DELIVERED,
            'text' => "Delivered"
        ],
        [
            'id' => Order::ORDER_STATUS_CANCELED,
            'text' => "Canceled"
        ]
    ];
}

/**
 * @param $id
 * @return string
 */
function get_order_status_name($id)
{
    $statuses = get_order_statuses();

    foreach ($statuses as $status) {
        if ($status['id'] == $id) {
            return $status['text'];
        }
    }

    return '';
}

/**
 * @return array[]
 */
function get_payment_statuses()
{
    return [
        [
            'id' => Order::PAYMENT_STATUS_PAID,
            'text' => "Paid"
        ],
        [
            'id' => Order::PAYMENT_STATUS_UNPAID,
            'text' => "Unpaid"
        ]
    ];
}

/**
 * @param $id
 * @return string
 */
function get_payment_status_name($id)
{
    $statuses = get_payment_statuses();

    foreach ($statuses as $status) {
        if ($status['id'] == $id) {
            return $status['text'];
        }
    }

    return '';
}

/**
 * @param string $message
 * @param array $result
 * @param int $code
 * @param string $redirect
 * @param string $delay
 * @return \Illuminate\Http\JsonResponse
 */
function make_success_response($message = '', $result = [], $code = 200, $redirect = '', $delay = '')
{
    $response = [
        'status' => True,
        'message' => $message
    ];

    if (!empty($result)) {
        $response['data'] = $result;
    }
    if (!empty($redirect)) {
        $response['redirect'] = $redirect;
    }
    if (!empty($delay)) {
        $response['delay'] = $delay;
    }

    return response()->json($response, $code);
}

/**
 * @param string $message
 * @param array $errors
 * @param int $code
 * @param string $redirect
 * @param string $delay
 * @return \Illuminate\Http\JsonResponse
 */
function make_error_response($message = '', $errors = [], $code = 404, $redirect = '', $delay = '')
{
    $response = [
        'status' => False,
        'message' => $message
    ];

    if (!empty($errors)) {
        $response['errors'] = $errors;
    }
    if (!empty($redirect)) {
        $response['redirect'] = $redirect;
    }
    if (!empty($delay)) {
        $response['delay'] = $delay;
    }

    return response()->json($response, $code);
}

/**
 * @param array $errors
 * @param string $message
 * @param int $code
 * @return \Illuminate\Http\JsonResponse
 */
function make_validation_error_response($errors = [], $message = 'The given data was invalid!', $code = 404, $redirect = '')
{
    $response = [
        'status' => False,
        'message' => $message,
        'errors' => $errors,
    ];

    if (!empty($redirect)) {
        $response['redirect'] = $redirect;
    }

    return response()->json($response, $code);
}

function auth_customer($key)
{
    return optional(Session::get('customer'))[$key];
}
