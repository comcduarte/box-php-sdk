<?php
declare(strict_types=1);
namespace comcduarte\Box\API\Enum;

enum OutcomeType: string
{
    case add_metadata  = 'add_metadata';
    case assign_task = 'assign_task';
    case copy_file = 'copy_file';
    case copy_folder = 'copy_folder';
    case create_folder = 'create_folder';
    case delete_file = 'delete_file';
    case delete_folder = 'delete_folder';
    case lock_file = 'lock_file';
    case move_file = 'move_file';
    case move_folder = 'move_folder';
    case remove_watermark_file = 'remove_watermark_file';
    case rename_folder = 'rename_folder';
    case restore_folder = 'restore_folder';
    case share_file = 'share_file';
    case share_folder = 'share_folder';
    case unlock_file = 'unlock_file';
    case upload_file = 'upload_file';
    case wait_for_task = 'wait_for_task';
    case watermark_file = 'watermark_file';
    case go_back_to_step = 'go_back_to_step';
    case apply_file_classification = 'apply_file_classification';
    case apply_folder_classification = 'apply_folder_classification';
    case send_notification = 'send_notification';
}