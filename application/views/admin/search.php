<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<ul class="dropdown-menu search-results animated fadeIn no-mtop display-block" id="top_search_dropdown">
    <?php
    $total = 0;
    foreach($result as $data){
       if(count($data['result']) > 0){
           $total++;
           ?>
           <li role="separator" class="divider"></li>
           <li class="dropdown-header"><?php echo e($data['search_heading']); ?></li>
       <?php } ?>
       <?php foreach($data['result'] as $_result){
        $output = '';
        switch($data['type']){
            case 'clients':
            $output = '<a href="'.admin_url('clients/client/'.$_result['userid']).'">'.e($_result['company']) .'</a>';
            break;
            case 'contacts':
            $output = '<a href="'.admin_url('clients/client/'.$_result['userid'].'?contactid='.$_result['id']).'">'. e($_result['firstname'] .' ' . $_result['lastname']) .' <small>'.e(get_company_name($_result['userid'])).'</small></a>';
            break;
            case 'staff':
            $output = '<a href="'.admin_url('staff/member/'.$_result['staffid']).'">'.e($_result['firstname']. ' ' . $_result['lastname']) .'</a>';
            break;
            case 'tickets':
            $output = '<a href="'.admin_url('tickets/ticket/'.$_result['ticketid']).'">#'.e($_result['ticketid'].' - '.$_result['subject']).'</a>';
            break;
            case 'knowledge_base_articles':
            if($_result['staff_article']) {
                $output = '<a href="'.admin_url('knowledge_base/view/'.$_result['slug']).'">'.e($_result['subject']).'</a>';
            } else {
                $output = '<a href="'.site_url('knowledge_base/'.$_result['slug']).'">'.e($_result['subject']).'</a>';
            }
            break;
            case 'leads':
            $output = '<a href="#" onclick="init_lead('.$_result['id'].');return false;">'.e($_result['name']).'</a>';
            break;
            case 'tasks':
            $task_link = 'init_task_modal('.$_result['id'].'); return false;';
            $output = '<a href="#" onclick="'.$task_link.'">'.e($_result['name']).'</a>';
            break;
            case 'contracts':
            $output = '<a href="'.admin_url('contracts/contract/'.$_result['id']).'">'.e($_result['subject']).'</a>';
            break;
            case 'invoice_payment_records':
            $output = '<a href="'.admin_url('payments/payment/'.$_result['paymentid']).'">#'.$_result['paymentid'].'<span class="pull-right">'.e(date('Y',strtotime($_result['date']))).'</span></a>';
            break;
            case 'invoices':
            $output = '<a href="'.admin_url('invoices/list_invoices/'.$_result['invoiceid']).'">'.e(format_invoice_number($_result['invoiceid'])).'<span class="pull-right">'.e(date('Y',strtotime($_result['date']))).'</span></a>';
            break;
            case 'credit_note':
            $output = '<a href="'.admin_url('credit_notes/list_credit_notes/'.$_result['credit_note_id']).'">'.e(format_credit_note_number($_result['credit_note_id'])).'<span class="pull-right">'.e(date('Y',strtotime($_result['date']))).'</span></a>';
            break;
            case 'estimates':
            $output = '<a href="'.admin_url('estimates/list_estimates/'.$_result['estimateid']).'">'.e(format_estimate_number($_result['estimateid'])).'<span class="pull-right">'.e(date('Y',strtotime($_result['date']))).'</span></a>';
            break;
            case 'expenses':
            $output = '<a href="'.admin_url('expenses/list_expenses/'.$_result['expenseid']).'">'.e($_result['category_name']). ' - ' .e(app_format_money($_result['amount'], $_result['currency_name'])).'</a>';
            break;
            case 'proposals':
            $output = '<a href="'.admin_url('proposals/list_proposals/'.$_result['id']).'">'.e(format_proposal_number($_result['id']) .' - ' . $_result['subject']) .'</a>';
            break;
            case 'custom_fields':
            $rel_data   = get_relation_data($_result['fieldto'], $_result['relid']);
            $rel_values = get_relation_values($rel_data, $_result['fieldto']);
            $output      = '<a class="pull-left" href="' . $rel_values['link'] . '">' . e($rel_values['name']) .'<span class="pull-right">'._l($_result['fieldto']).'</span></a>';
            break;
            case 'invoice_items':
            $output = '<a href="'.admin_url('invoices/list_invoices/'.$_result['rel_id']).'">'.e(format_invoice_number($_result['rel_id']));
            $output .= '<br />';
            $output .= '<small>'.e($_result['description']).'</small>';
            $output .= '</a>';
            break;
            case 'estimate_items':
            $output = '<a href="'.admin_url('estimates/list_estimates/'.$_result['rel_id']).'">'.e(format_estimate_number($_result['rel_id']));
            $output .= '<br />';
            $output .= '<small>'.e($_result['description']).'</small>';
            $output .= '</a>';
            break;
            case 'projects':
            $output = '<a href="'.admin_url('projects/view/'.$_result['id']).'">'.e($_result['name']).'</a>';
            break;
        }
        ?>
        <li><?php echo hooks()->apply_filters('global_search_result_output', $output, ['result'=>$_result, 'type'=>$data['type']]); ?></li>
    <?php } ?>
<?php } ?>
<?php if($total == 0){ ?>
    <li class="padding-5 text-center search-no-results"><?php echo _l('not_results_found'); ?></li>
<?php } ?>
</ul>
