<?php

namespace App\Enums;

enum Permissions: string
{
    // Dues
    case LIST_DUES = 'list dues';
    case VIEW_DUES = 'view dues';
    case CREATE_DUES = 'create dues';
    case EDIT_DUES = 'edit dues';
    case DELETE_DUES = 'delete dues';

    // Users
    case MANAGE_USERS = 'manage users';

    // Sites
    case MANAGE_SITES = 'manage sites';

    // ... Diğer tüm yetkiler buraya eklenebilir
    // ...

    // Dashboard
    case VIEW_DASHBOARD = 'view dashboard';


    /**
     * Enum değerlerini bir dizi olarak döndürür.
     * Seeder'larda kullanmak için çok faydalıdır.
     */
    public static function all(): array
    {
        return array_column(self::cases(), 'value');
    }
}
