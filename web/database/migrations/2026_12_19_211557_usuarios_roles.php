<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Crear roles en la base de datos
        DB::unprepared(<<<SQL
            CREATE ROLE IF NOT EXISTS 'db_login';
            CREATE ROLE IF NOT EXISTS 'db_api';
            CREATE ROLE IF NOT EXISTS 'administrador';
            CREATE ROLE IF NOT EXISTS 'operador';
            CREATE ROLE IF NOT EXISTS 'auditor';
            CREATE ROLE IF NOT EXISTS 'recovery';
        SQL);
        
        // Asignar permisos a los roles
        DB::unprepared(<<<SQL
        GRANT SELECT ON laravel.usuarios TO 'db_login';
        GRANT SELECT ON laravel.departamentos TO 'db_login';
        GRANT INSERT ON laravel.alertas TO 'db_api';
        GRANT SELECT ON laravel.dispositivos TO 'db_api';
        GRANT SELECT, DELETE, UPDATE, INSERT ON laravel.departamentos to 'administrador';
        GRANT SELECT, UPDATE, INSERT ON laravel.usuarios to 'administrador';
        GRANT SELECT, UPDATE, INSERT ON laravel.dispositivos to 'administrador';
        GRANT SELECT, DELETE, UPDATE, INSERT ON laravel.dashboards to 'administrador';
        GRANT SELECT, UPDATE ON laravel.alertas to 'administrador';
        GRANT SELECT, DELETE, UPDATE, INSERT ON laravel.asigna to 'administrador';
        GRANT SELECT, UPDATE, INSERT ON laravel.informes to 'administrador';
        GRANT INSERT, SELECT ON laravel.activity_log to 'administrador';
        GRANT SELECT, UPDATE ON laravel.usuarios to 'operador';
        GRANT SELECT ON laravel.dispositivos to 'operador';
        GRANT SELECT ON laravel.departamentos to 'operador';
        GRANT SELECT ON laravel.dashboards to 'operador';
        GRANT SELECT, UPDATE ON laravel.alertas to 'operador';
        GRANT SELECT, DELETE, UPDATE, INSERT ON laravel.asigna to 'operador';
        GRANT SELECT, UPDATE, INSERT ON laravel.informes to 'operador';
        GRANT INSERT ON laravel.activity_log to 'operador';
        GRANT SELECT ON laravel.asigna to 'auditor';
        GRANT SELECT ON laravel.alertas to 'auditor';
        GRANT SELECT ON laravel.informes to 'auditor';
        GRANT SELECT ON laravel.activity_log to 'auditor';
        GRANT SELECT ON laravel.dispositivos to 'auditor';
        GRANT SELECT ON laravel.departamentos to 'recovery';
        GRANT SELECT, UPDATE, INSERT ON laravel.usuarios to 'recovery';
        GRANT INSERT ON laravel.activity_log to 'recovery';
        FLUSH PRIVILEGES;
        SQL);

        // Crear usuarios y asignarles roles
        DB::unprepared(<<<SQL
        CREATE USER IF NOT EXISTS 'web_login'@'%' IDENTIFIED BY 'loginpass';
        CREATE USER IF NOT EXISTS 'web_api'@'%' IDENTIFIED BY 'apipass';
        CREATE USER IF NOT EXISTS 'web_admin'@'%' IDENTIFIED BY 'adminpass';
        CREATE USER IF NOT EXISTS 'web_op'@'%' IDENTIFIED BY 'oppass';
        CREATE USER IF NOT EXISTS 'web_auditor'@'%' IDENTIFIED BY 'auditorpass';
        CREATE USER IF NOT EXISTS 'web_recovery'@'%' IDENTIFIED BY 'recoverypass';
    
        GRANT 'db_login' TO 'web_login'@'%';
        GRANT 'db_api' TO 'web_api'@'%';
        GRANT 'administrador' TO 'web_admin'@'%';
        GRANT 'operador' TO 'web_op'@'%';
        GRANT 'auditor' TO 'web_auditor'@'%';
        GRANT 'recovery' TO 'web_recovery'@'%';
        SET DEFAULT ROLE 'db_login' TO 'web_login'@'%';
        SET DEFAULT ROLE 'db_api' TO 'web_api'@'%';
        SET DEFAULT ROLE 'administrador' TO 'web_admin'@'%';
        SET DEFAULT ROLE 'operador' TO 'web_op'@'%';
        SET DEFAULT ROLE 'recovery' TO 'web_recovery'@'%';
        SET DEFAULT ROLE 'auditor' TO 'web_auditor'@'%';
        SQL);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared(<<<SQL
            DROP ROLE IF EXISTS 'db_login';
            DROP ROLE IF EXISTS 'db_api';
            DROP ROLE IF EXISTS 'administrador';
            DROP ROLE IF EXISTS 'operador';
            DROP ROLE IF EXISTS 'auditor';
            DROP ROLE IF EXISTS 'recovery';
        SQL);
        DB::unprepared(<<<SQL
            DROP USER IF EXISTS 'web_login'@'%';
            DROP USER IF EXISTS 'web_api'@'%';
            DROP USER IF EXISTS 'web_admin'@'%';
            DROP USER IF EXISTS 'web_op'@'%';
            DROP USER IF EXISTS 'web_auditor'@'%';
            DROP USER IF EXISTS 'web_recovery'@'%';
        SQL);
    }
};
