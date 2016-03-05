<?php

namespace T4web\Migrations\Migration;

interface MigrationInterface
{
    /**
     * Apply migration
     */
    public function up();

    /**
     * Rollback migration
     */
    public function down();
}
