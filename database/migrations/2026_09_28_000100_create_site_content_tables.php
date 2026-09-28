<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('professionals', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('profession');
            $table->string('registration')->nullable(); // ex.: CRP 06/000000
            $table->string('short_bio', 500)->nullable();
            $table->longText('bio')->nullable();
            $table->longText('education')->nullable();
            $table->json('specialties')->nullable();
            $table->string('photo')->nullable();
            $table->string('whatsapp', 20)->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort')->default(0);
            $table->timestamps();
        });

        Schema::create('therapies', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('summary', 500);
            $table->longText('body')->nullable();
            $table->text('for_whom')->nullable();
            $table->string('icon')->nullable();
            $table->string('image')->nullable();
            $table->string('meta_description', 300)->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort')->default(0);
            $table->timestamps();
        });

        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('summary', 500);
            $table->longText('body')->nullable();
            $table->unsignedInteger('workload_hours')->nullable();
            $table->string('duration_text')->nullable();
            $table->string('format')->default('hibrido'); // presencial | online | hibrido
            $table->text('prerequisites')->nullable();
            $table->text('certification')->nullable();
            $table->string('price_text')->nullable();
            $table->string('checkout_url')->nullable();
            $table->string('cover')->nullable();
            $table->string('meta_description', 300)->nullable();
            $table->boolean('is_flagship')->default(false);
            $table->string('status')->default('publicado'); // publicado | rascunho
            $table->unsignedInteger('sort')->default(0);
            $table->timestamps();
        });

        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('author');
            $table->string('cover')->nullable();
            $table->string('synopsis', 600);
            $table->longText('description')->nullable();
            $table->string('purchase_url')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->string('category')->nullable();
            $table->string('status')->default('publicado'); // publicado | rascunho | esgotado
            $table->boolean('is_featured')->default(false);
            $table->unsignedInteger('sort')->default(0);
            $table->timestamps();
        });

        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('cover')->nullable();
            $table->string('excerpt', 500)->nullable();
            $table->longText('body');
            $table->foreignId('author_id')->nullable()->constrained('professionals')->nullOnDelete();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('status')->default('rascunho'); // rascunho | publicado | agendado
            $table->timestamp('published_at')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_description', 300)->nullable();
            $table->timestamps();

            $table->index(['status', 'published_at']);
        });

        Schema::create('post_tag', function (Blueprint $table) {
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tag_id')->constrained()->cascadeOnDelete();
            $table->primary(['post_id', 'tag_id']);
        });

        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('kind')->default('paciente'); // paciente | aluno
            $table->text('content');
            $table->string('status')->default('pendente'); // pendente | aprovado | rejeitado
            $table->boolean('is_featured')->default(false);
            $table->timestamp('consent_at')->nullable();
            $table->unsignedInteger('sort')->default(0);
            $table->timestamps();
        });

        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->string('question');
            $table->text('answer');
            $table->string('group')->default('geral'); // geral | clinica | formacao
            $table->boolean('show_on_home')->default(false);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort')->default(0);
            $table->timestamps();
        });

        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // contato | agendamento | inscricao_curso
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone', 30);
            $table->text('message')->nullable();
            $table->string('modality')->nullable(); // presencial | online | indiferente
            $table->foreignId('course_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('therapy_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('professional_id')->nullable()->constrained()->nullOnDelete();
            $table->string('source_url', 500)->nullable();
            $table->string('utm_source')->nullable();
            $table->string('utm_medium')->nullable();
            $table->string('utm_campaign')->nullable();
            $table->string('utm_content')->nullable();
            $table->string('utm_term')->nullable();
            $table->string('gclid')->nullable();
            $table->string('fbclid')->nullable();
            $table->timestamp('consent_at');
            $table->string('ip_hash', 64)->nullable();
            $table->string('status')->default('novo'); // novo | em_atendimento | concluido
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
        });

        Schema::create('settings', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->text('value')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        foreach (['settings', 'leads', 'faqs', 'testimonials', 'post_tag', 'posts', 'tags', 'categories', 'books', 'courses', 'therapies', 'professionals'] as $table) {
            Schema::dropIfExists($table);
        }
    }
};
