<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Module;
use App\Models\User;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run(): void {
        $admin = User::where('role','admin')->first();

        $course = Course::updateOrCreate(
            ['slug' => 'conduccion-teorica-automovil'],
            [
                'title' => 'Aprendizaje teórico de conducción',
                'description' => 'Curso introductorio para el aprendizaje teórico de la conducción de automóviles particulares en Guatemala.',
                'status' => 'published',
                'sort_order' => 1,
                'created_by' => $admin?->id,
            ]
        );

        $module1 = Module::updateOrCreate(
            ['course_id' => $course->id,'slug' => 'licencia-y-obligaciones'],
            [
                'title' => 'Licencia de conducir y obligaciones',
                'description' => 'Conceptos generales sobre la licencia tipo C y las principales obligaciones del conductor.',
                'status' => 'published',
                'sort_order' => 1,
            ]
        );

        $module2 = Module::updateOrCreate(
            ['course_id' => $course->id,'slug' => 'via-publica-y-senalizacion'],
            [
                'title' => 'Vía pública y señalización',
                'description' => 'Principios generales sobre la vía pública y las señales utilizadas para regular el tránsito.',
                'status' => 'published',
                'sort_order' => 2,
            ]
        );

        Lesson::updateOrCreate(
            ['module_id' => $module1->id,'slug' => 'licencia-tipo-c'],
            [
                'title' => 'Licencia tipo C',
                'summary' => 'Introducción a la licencia de conducir tipo C.',
                'content' => 'Contenido educativo preliminar sobre la licencia tipo C. Este contenido podrá ser editado posteriormente desde el CMS.',
                'estimated_minutes' => 8,
                'status' => 'published',
                'sort_order' => 1,
            ]
        );

        Lesson::updateOrCreate(
            ['module_id' => $module1->id,'slug' => 'obligaciones-del-conductor'],
            [
                'title' => 'Obligaciones del conductor',
                'summary' => 'Principales responsabilidades de una persona al conducir.',
                'content' => 'Contenido educativo preliminar sobre las obligaciones del conductor.',
                'estimated_minutes' => 10,
                'status' => 'published',
                'sort_order' => 2,
            ]
        );

        Lesson::updateOrCreate(
            ['module_id' => $module2->id,'slug' => 'conceptos-de-via-publica'],
            [
                'title' => 'Conceptos de vía pública',
                'summary' => 'Conceptos fundamentales relacionados con la circulación en la vía pública.',
                'content' => 'Contenido educativo preliminar sobre la vía pública.',
                'estimated_minutes' => 7,
                'status' => 'published',
                'sort_order' => 1,
            ]
        );

        Lesson::updateOrCreate(
            ['module_id' => $module2->id,'slug' => 'introduccion-a-la-senalizacion'],
            [
                'title' => 'Introducción a la señalización vial',
                'summary' => 'Principios generales de las señales de tránsito.',
                'content' => 'Contenido educativo preliminar sobre señalización vial.',
                'estimated_minutes' => 9,
                'status' => 'published',
                'sort_order' => 2,
            ]
        );
    }
}