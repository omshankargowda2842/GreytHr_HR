<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('jobs_lists', function (Blueprint $table) {
            $table->string('job_id')->primary();
            $table->string('contact_email');
            $table->string('title');
            $table->text('description');
            $table->string('location');
            $table->decimal('salary', 10, 2);
            $table->string('company_id');
            $table->string('company_name');
            $table->string('hr_name');
            $table->date('expire_date');
            $table->string('contact_phone');
            $table->integer('vacancies');
            $table->string('education_requirement');
            $table->string('experience_requirement');
            $table->string('skills_required');
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            // Additional Fields
            $table->string('application_link')->nullable(); // Link to the application page
            $table->string('job_type')->default('Full-time');

            $table->text('responsibilities')->nullable(); // Job responsibilities
            $table->text('benefits')->nullable(); // Job benefits
            $table->text('application_instructions')->nullable();
            $table->timestamps();
        });

        $triggerSQL = <<<SQL
        CREATE TRIGGER generate_job_id BEFORE INSERT ON jobs_lists FOR EACH ROW
        BEGIN
            DECLARE max_id INT;

            -- Find the maximum job_id value in the jobs_lists table
            SELECT IFNULL(MAX(CAST(SUBSTRING(job_id, 4) AS UNSIGNED)) + 1, 1) INTO max_id FROM jobs_lists;

            -- Increment the max_id and assign it to the new job_id
            SET NEW.job_id = CONCAT("JOB", LPAD(max_id, 4, "0"));
        END;
        SQL;

        DB::unprepared($triggerSQL);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs_lists');
    }
};
