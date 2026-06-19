<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Option;
use App\Models\User;

class QuizSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Use one demo teacher for all seeded quizzes so the student side is clean.
        $teacher1 = User::where('email', 'john.smith@gmail.com')->first();

        if (!$teacher1) {
            $this->command->error('John Smith teacher account not found. Please run UserSeeder first.');
            return;
        }

        // ============================================================
        // Quiz 1: Laravel Fundamentals (Teacher 1)
        // ============================================================
        $quiz1 = Quiz::create([
            'teacher_id' => $teacher1->id,
            'title' => 'Laravel Fundamentals',
            'description' => 'Test your knowledge of Laravel basics including routing, controllers, and models.',
            'duration' => 30,
            'passing_score' => 70,
            'is_published' => true,
        ]);

        // Question 1.1
        $question1_1 = Question::create([
            'quiz_id' => $quiz1->id,
            'question_text' => 'What is the main configuration file for Laravel?',
            'type' => 'multiple_choice',
            'points' => 5,
            'order' => 1,
        ]);

        Option::create(['question_id' => $question1_1->id, 'option_text' => 'config/app.php', 'is_correct' => true, 'order' => 1]);
        Option::create(['question_id' => $question1_1->id, 'option_text' => 'app/config.php', 'is_correct' => false, 'order' => 2]);
        Option::create(['question_id' => $question1_1->id, 'option_text' => 'app.php', 'is_correct' => false, 'order' => 3]);
        Option::create(['question_id' => $question1_1->id, 'option_text' => 'config/main.php', 'is_correct' => false, 'order' => 4]);

        // Question 1.2
        $question1_2 = Question::create([
            'quiz_id' => $quiz1->id,
            'question_text' => 'Which command creates a new Laravel project?',
            'type' => 'multiple_choice',
            'points' => 5,
            'order' => 2,
        ]);

        Option::create(['question_id' => $question1_2->id, 'option_text' => 'composer create-project laravel/laravel', 'is_correct' => true, 'order' => 1]);
        Option::create(['question_id' => $question1_2->id, 'option_text' => 'npm create laravel', 'is_correct' => false, 'order' => 2]);
        Option::create(['question_id' => $question1_2->id, 'option_text' => 'laravel new project', 'is_correct' => false, 'order' => 3]);
        Option::create(['question_id' => $question1_2->id, 'option_text' => 'git clone laravel/laravel', 'is_correct' => false, 'order' => 4]);

        // Question 1.3
        $question1_3 = Question::create([
            'quiz_id' => $quiz1->id,
            'question_text' => 'What does ORM stand for?',
            'type' => 'multiple_choice',
            'points' => 5,
            'order' => 3,
        ]);

        Option::create(['question_id' => $question1_3->id, 'option_text' => 'Object-Relational Mapping', 'is_correct' => true, 'order' => 1]);
        Option::create(['question_id' => $question1_3->id, 'option_text' => 'Object-Readable Model', 'is_correct' => false, 'order' => 2]);
        Option::create(['question_id' => $question1_3->id, 'option_text' => 'Online Resource Manager', 'is_correct' => false, 'order' => 3]);
        Option::create(['question_id' => $question1_3->id, 'option_text' => 'Object Response Middleware', 'is_correct' => false, 'order' => 4]);

        // Question 1.4
        $question1_4 = Question::create([
            'quiz_id' => $quiz1->id,
            'question_text' => 'Laravel uses Eloquent as its ORM.',
            'type' => 'true_false',
            'points' => 5,
            'order' => 4,
        ]);

        Option::create(['question_id' => $question1_4->id, 'option_text' => 'True', 'is_correct' => true, 'order' => 1]);
        Option::create(['question_id' => $question1_4->id, 'option_text' => 'False', 'is_correct' => false, 'order' => 2]);

        // Question 1.5
        $question1_5 = Question::create([
            'quiz_id' => $quiz1->id,
            'question_text' => 'What is the default port for Laravel development server?',
            'type' => 'multiple_choice',
            'points' => 5,
            'order' => 5,
        ]);

        Option::create(['question_id' => $question1_5->id, 'option_text' => '8000', 'is_correct' => true, 'order' => 1]);
        Option::create(['question_id' => $question1_5->id, 'option_text' => '3000', 'is_correct' => false, 'order' => 2]);
        Option::create(['question_id' => $question1_5->id, 'option_text' => '5000', 'is_correct' => false, 'order' => 3]);
        Option::create(['question_id' => $question1_5->id, 'option_text' => '8080', 'is_correct' => false, 'order' => 4]);

        // ============================================================
        // Quiz 2: PHP Advanced Concepts (John Smith)
        // ============================================================
        $quiz2 = Quiz::create([
            'teacher_id' => $teacher1->id,
            'title' => 'PHP Advanced Concepts',
            'description' => 'Advanced PHP concepts including namespaces, traits, and design patterns.',
            'duration' => 45,
            'passing_score' => 75,
            'is_published' => true,
        ]);

        // Question 2.1
        $question2_1 = Question::create([
            'quiz_id' => $quiz2->id,
            'question_text' => 'What is a PHP namespace used for?',
            'type' => 'multiple_choice',
            'points' => 5,
            'order' => 1,
        ]);

        Option::create(['question_id' => $question2_1->id, 'option_text' => 'To organize code and avoid naming conflicts', 'is_correct' => true, 'order' => 1]);
        Option::create(['question_id' => $question2_1->id, 'option_text' => 'To improve performance', 'is_correct' => false, 'order' => 2]);
        Option::create(['question_id' => $question2_1->id, 'option_text' => 'To enhance security', 'is_correct' => false, 'order' => 3]);
        Option::create(['question_id' => $question2_1->id, 'option_text' => 'To reduce file size', 'is_correct' => false, 'order' => 4]);

        // Question 2.2
        $question2_2 = Question::create([
            'quiz_id' => $quiz2->id,
            'question_text' => 'PHP supports multiple inheritance.',
            'type' => 'true_false',
            'points' => 5,
            'order' => 2,
        ]);

        Option::create(['question_id' => $question2_2->id, 'option_text' => 'True', 'is_correct' => false, 'order' => 1]);
        Option::create(['question_id' => $question2_2->id, 'option_text' => 'False', 'is_correct' => true, 'order' => 2]);

        // Question 2.3
        $question2_3 = Question::create([
            'quiz_id' => $quiz2->id,
            'question_text' => 'What is a Trait in PHP?',
            'type' => 'multiple_choice',
            'points' => 5,
            'order' => 3,
        ]);

        Option::create(['question_id' => $question2_3->id, 'option_text' => 'A mechanism for reusing methods in single inheritance languages', 'is_correct' => true, 'order' => 1]);
        Option::create(['question_id' => $question2_3->id, 'option_text' => 'A deprecated feature', 'is_correct' => false, 'order' => 2]);
        Option::create(['question_id' => $question2_3->id, 'option_text' => 'A security feature', 'is_correct' => false, 'order' => 3]);
        Option::create(['question_id' => $question2_3->id, 'option_text' => 'A database feature', 'is_correct' => false, 'order' => 4]);

        // Question 2.4
        $question2_4 = Question::create([
            'quiz_id' => $quiz2->id,
            'question_text' => 'What is dependency injection?',
            'type' => 'multiple_choice',
            'points' => 10,
            'order' => 4,
        ]);

        Option::create(['question_id' => $question2_4->id, 'option_text' => 'A design pattern where objects receive their dependencies from external sources', 'is_correct' => true, 'order' => 1]);
        Option::create(['question_id' => $question2_4->id, 'option_text' => 'A way to inject code into databases', 'is_correct' => false, 'order' => 2]);
        Option::create(['question_id' => $question2_4->id, 'option_text' => 'A security vulnerability', 'is_correct' => false, 'order' => 3]);
        Option::create(['question_id' => $question2_4->id, 'option_text' => 'A PHP extension', 'is_correct' => false, 'order' => 4]);

        // Question 2.5
        $question2_5 = Question::create([
            'quiz_id' => $quiz2->id,
            'question_text' => 'Which of the following is not a PHP magic method?',
            'type' => 'multiple_choice',
            'points' => 5,
            'order' => 5,
        ]);

        Option::create(['question_id' => $question2_5->id, 'option_text' => '__construct', 'is_correct' => false, 'order' => 1]);
        Option::create(['question_id' => $question2_5->id, 'option_text' => '__toString', 'is_correct' => false, 'order' => 2]);
        Option::create(['question_id' => $question2_5->id, 'option_text' => '__initialize', 'is_correct' => true, 'order' => 3]);
        Option::create(['question_id' => $question2_5->id, 'option_text' => '__invoke', 'is_correct' => false, 'order' => 4]);

        // ============================================================
        // Quiz 3: Database Design & SQL (John Smith)
        // ============================================================
        $quiz3 = Quiz::create([
            'teacher_id' => $teacher1->id,
            'title' => 'Database Design & SQL',
            'description' => 'Learn about database design principles, normalization, and SQL queries.',
            'duration' => 50,
            'passing_score' => 72,
            'is_published' => true,
        ]);

        // Question 3.1
        $question3_1 = Question::create([
            'quiz_id' => $quiz3->id,
            'question_text' => 'What is database normalization?',
            'type' => 'multiple_choice',
            'points' => 5,
            'order' => 1,
        ]);

        Option::create(['question_id' => $question3_1->id, 'option_text' => 'A process to organize database tables to reduce redundancy', 'is_correct' => true, 'order' => 1]);
        Option::create(['question_id' => $question3_1->id, 'option_text' => 'A way to convert database to uppercase', 'is_correct' => false, 'order' => 2]);
        Option::create(['question_id' => $question3_1->id, 'option_text' => 'A backup procedure', 'is_correct' => false, 'order' => 3]);
        Option::create(['question_id' => $question3_1->id, 'option_text' => 'A security feature', 'is_correct' => false, 'order' => 4]);

        // Question 3.2
        $question3_2 = Question::create([
            'quiz_id' => $quiz3->id,
            'question_text' => 'Which normal form ensures that no non-key attribute depends on another non-key attribute?',
            'type' => 'multiple_choice',
            'points' => 5,
            'order' => 2,
        ]);

        Option::create(['question_id' => $question3_2->id, 'option_text' => 'Third Normal Form (3NF)', 'is_correct' => true, 'order' => 1]);
        Option::create(['question_id' => $question3_2->id, 'option_text' => 'First Normal Form (1NF)', 'is_correct' => false, 'order' => 2]);
        Option::create(['question_id' => $question3_2->id, 'option_text' => 'Second Normal Form (2NF)', 'is_correct' => false, 'order' => 3]);
        Option::create(['question_id' => $question3_2->id, 'option_text' => 'Zero Normal Form (0NF)', 'is_correct' => false, 'order' => 4]);

        // Question 3.3
        $question3_3 = Question::create([
            'quiz_id' => $quiz3->id,
            'question_text' => 'What does ACID stand for in database transactions?',
            'type' => 'multiple_choice',
            'points' => 5,
            'order' => 3,
        ]);

        Option::create(['question_id' => $question3_3->id, 'option_text' => 'Atomicity, Consistency, Isolation, Durability', 'is_correct' => true, 'order' => 1]);
        Option::create(['question_id' => $question3_3->id, 'option_text' => 'Automatic, Compatible, Indexing, Database', 'is_correct' => false, 'order' => 2]);
        Option::create(['question_id' => $question3_3->id, 'option_text' => 'Availability, Coherence, Integrity, Deletion', 'is_correct' => false, 'order' => 3]);
        Option::create(['question_id' => $question3_3->id, 'option_text' => 'Access, Control, Integration, Data', 'is_correct' => false, 'order' => 4]);

        // Question 3.4
        $question3_4 = Question::create([
            'quiz_id' => $quiz3->id,
            'question_text' => 'A foreign key is a field that references the primary key of another table.',
            'type' => 'true_false',
            'points' => 5,
            'order' => 4,
        ]);

        Option::create(['question_id' => $question3_4->id, 'option_text' => 'True', 'is_correct' => true, 'order' => 1]);
        Option::create(['question_id' => $question3_4->id, 'option_text' => 'False', 'is_correct' => false, 'order' => 2]);

        // Question 3.5
        $question3_5 = Question::create([
            'quiz_id' => $quiz3->id,
            'question_text' => 'What is the purpose of an INDEX in a database?',
            'type' => 'multiple_choice',
            'points' => 5,
            'order' => 5,
        ]);

        Option::create(['question_id' => $question3_5->id, 'option_text' => 'To speed up data retrieval operations', 'is_correct' => true, 'order' => 1]);
        Option::create(['question_id' => $question3_5->id, 'option_text' => 'To encrypt data', 'is_correct' => false, 'order' => 2]);
        Option::create(['question_id' => $question3_5->id, 'option_text' => 'To reduce table size', 'is_correct' => false, 'order' => 3]);
        Option::create(['question_id' => $question3_5->id, 'option_text' => 'To backup data', 'is_correct' => false, 'order' => 4]);

        // ============================================================
        // Quiz 4: Web Development Best Practices (Draft - Unpublished)
        // ============================================================
        $quiz4 = Quiz::create([
            'teacher_id' => $teacher1->id,
            'title' => 'Web Development Best Practices',
            'description' => 'Best practices and standards for modern web development.',
            'duration' => 60,
            'passing_score' => 80,
            'is_published' => false,
        ]);

        // Question 4.1
        $question4_1 = Question::create([
            'quiz_id' => $quiz4->id,
            'question_text' => 'What is RESTful API?',
            'type' => 'multiple_choice',
            'points' => 10,
            'order' => 1,
        ]);

        Option::create(['question_id' => $question4_1->id, 'option_text' => 'An architectural style for designing networked applications', 'is_correct' => true, 'order' => 1]);
        Option::create(['question_id' => $question4_1->id, 'option_text' => 'A programming language', 'is_correct' => false, 'order' => 2]);
        Option::create(['question_id' => $question4_1->id, 'option_text' => 'A database management system', 'is_correct' => false, 'order' => 3]);
        Option::create(['question_id' => $question4_1->id, 'option_text' => 'A security protocol', 'is_correct' => false, 'order' => 4]);

        $this->command->info('Quizzes seeded successfully!');
        $this->command->info('');
        $this->command->info('Quizzes Created:');
        $this->command->info('  - Laravel Fundamentals (5 questions)');
        $this->command->info('  - PHP Advanced Concepts (5 questions)');
        $this->command->info('  - Database Design & SQL (5 questions)');
        $this->command->info('  - Web Development Best Practices (1 question - Draft)');
    }
}