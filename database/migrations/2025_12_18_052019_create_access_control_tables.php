<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. Tabel Roles (Cek jika belum ada)
        if (!Schema::hasTable('roles')) {
            Schema::create('roles', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('label')->nullable();
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        // 2. Tabel Menu (Cek jika belum ada)
        if (!Schema::hasTable('menu')) {
            Schema::create('menu', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('url');
                $table->string('route_name');
                $table->string('icon')->nullable();
                $table->integer('order')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // 3. Tabel Routes (Cek jika belum ada)
        if (!Schema::hasTable('app_routes')) {
            Schema::create('app_routes', function (Blueprint $table) {
                $table->id();
                $table->string('url');
                $table->string('controller');
                $table->string('route_name')->nullable();
                $table->string('method')->default('GET');
                $table->timestamps();
            });
        }

        // 4. Tabel Permissions (Cek jika belum ada)
        if (!Schema::hasTable('permissions')) {
            Schema::create('permissions', function (Blueprint $table) {
                $table->id();
                // INI PENTING: Relasi ke menu agar permission bisa dikelompokkan per menu
                $table->foreignId('menu_id')->nullable()->constrained('menu')->onDelete('cascade');

                $table->string('name'); // Kode (ex: cars.view)
                $table->string('label'); // Label (ex: Lihat Mobil)
                $table->string('guard_name')->default('web');
                $table->timestamps();
            });
        }

        // 5. Update Tabel Users (PERBAIKAN: Cek kolom satu per satu)
        Schema::table('users', function (Blueprint $table) {
            // Cek apakah kolom role_id sudah ada?
            if (!Schema::hasColumn('users', 'role_id')) {
                $table->foreignId('role_id')->nullable()->constrained('roles')->onDelete('set null');
            }
            // Cek apakah kolom phone sudah ada?
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable();
            }
            // Cek apakah kolom avatar sudah ada?
            if (!Schema::hasColumn('users', 'avatar')) {
                $table->string('avatar')->nullable();
            }
            // Cek apakah kolom is_active sudah ada?
            if (!Schema::hasColumn('users', 'is_active')) {
                $table->boolean('is_active')->default(true);
            }
        });

        // 6. Pivot: Role ke Menu
        if (!Schema::hasTable('role_has_menus')) {
            Schema::create('role_has_menus', function (Blueprint $table) {
                $table->foreignId('role_id')->constrained('roles')->onDelete('cascade');
                $table->foreignId('menu_id')->constrained('menu')->onDelete('cascade');
                $table->primary(['role_id', 'menu_id']);
            });
        }

        // 7. Pivot: Role ke Permission
        if (!Schema::hasTable('role_has_permissions')) {
            Schema::create('role_has_permissions', function (Blueprint $table) {
                $table->foreignId('role_id')->constrained('roles')->onDelete('cascade');
                $table->foreignId('permission_id')->constrained('permissions')->onDelete('cascade');
                $table->primary(['role_id', 'permission_id']);
            });
        }
    }

    public function down()
    {
        // Hapus tabel pivot dulu
        Schema::dropIfExists('role_has_permissions');
        Schema::dropIfExists('role_has_menus');

        // Hapus kolom tambahan di users
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'role_id')) {
                $table->dropForeign(['role_id']);
                $table->dropColumn('role_id');
            }
            if (Schema::hasColumn('users', 'phone')) {
                $table->dropColumn('phone');
            }
            if (Schema::hasColumn('users', 'avatar')) {
                $table->dropColumn('avatar');
            }
            if (Schema::hasColumn('users', 'is_active')) {
                $table->dropColumn('is_active');
            }
        });

        Schema::dropIfExists('permissions');
        Schema::dropIfExists('app_routes');
        Schema::dropIfExists('menu');
        Schema::dropIfExists('roles');
    }
};
