<?php

namespace App\Support\RouteDefinitions\ShoppingArea;

use App\Http\Controllers\ShoppingArea\PaymentApprovalController;

class PaymentApprovalRoutes
{
    public static function all(): array
    {
        return [
            //PaymentApproval

            [
                'uri' => 'payment_approval/index',
                'method' => 'get',
                'action' => [PaymentApprovalController::class, 'index'],
                'permission' => true,
                'name' => 'payment.approval.index',
            ],
            [
                'uri' => 'payment_approval/getPaymentApproval',
                'method' => 'get',
                'action' => [PaymentApprovalController::class, 'getPaymentApproval'],
                'permission' => true,
                'name' => 'payment.approval.getPaymentApproval',
            ],
            [
                'uri' => 'payment_approval/searchPaymentApproval',
                'method' => 'post',
                'action' => [PaymentApprovalController::class, 'searchPaymentApproval'],
                'permission' => true,
                'name' => 'payment.approval.searchPaymentApproval',
            ],
            [
                'uri' => 'payment_approval/store',
                'method' => 'post',
                'action' => [PaymentApprovalController::class, 'store'],
                'permission' => true,
                'name' => 'payment.approval.store',
            ],
            [
                'uri' => 'payment_approval/document/{id}',
                'method' => 'post',
                'action' => [PaymentApprovalController::class, 'document'],
                'permission' => true,
                'name' => 'payment.approval.document',
            ],
            [
                'uri' => 'payment_approval/rejected/{id}',
                'method' => 'post',
                'action' => [PaymentApprovalController::class, 'rejected'],
                'permission' => true,
                'name' => 'payment.approval.rejected',
            ],
            [
                'uri' => 'payment_approval/rejected_vericom/{id}',
                'method' => 'get',
                'action' => [PaymentApprovalController::class, 'rejected_vericom'],
                'permission' => true,
                'name' => 'payment.approval.rejected_vericom',
            ],
            [
                'uri' => 'payment_approval/download_document/{id}/kind/{kind}',
                'method' => 'get',
                'action' => [PaymentApprovalController::class, 'download_document'],
                'permission' => true,
                'name' => 'payment.approval.show_document',
            ],
            [
                'uri' => 'payment_approval/delete/{id}',
                'method' => 'delete',
                'action' => [PaymentApprovalController::class, 'delete'],
                'permission' => true,
                'name' => 'payment.approval.delete',
            ],
            [
                'uri' => 'payment_approval/alarm_pending_payments',
                'method' => 'get',
                'action' => [PaymentApprovalController::class, 'pending_payments'],
                'permission' => true,
                'name' => 'payment.approval.alarm.pending.payments',
            ],
            [
                'uri' => 'payment_approval/validate_payment/{id}',
                'method' => 'put',
                'action' => [PaymentApprovalController::class, 'validate_payment'],
                'permission' => true,
                'name' => 'payment.approval.validate',
            ],
        ];
    }
}
