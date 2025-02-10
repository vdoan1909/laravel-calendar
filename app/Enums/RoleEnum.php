<?php
namespace App\Enums;

enum RoleEnum: int
{
    case LECTURER = 0; // giảng viên
    case STUDENT = 1; // học sinh
}