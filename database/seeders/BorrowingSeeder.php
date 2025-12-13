<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Borrowing;
use App\Models\Member;
use App\Models\Book;

class BorrowingSeeder extends Seeder
{
    public function run(): void
    {
        $members = Member::all();
        $books = Book::all();

        if ($members->isEmpty() || $books->isEmpty()) {
            $this->command->warn('Please seed members and books first!');
            return;
        }

        $statuses = ['borrowed', 'returned', 'overdue'];

        for ($i = 1; $i <= 25; $i++) {
            $borrowDate = now()->subDays(rand(1, 60));
            $dueDate = $borrowDate->copy()->addDays(14);
            $status = $statuses[array_rand($statuses)];
            
            Borrowing::create([
                'member_id' => $members->random()->id,
                'book_id' => $books->random()->id,
                'borrow_date' => $borrowDate,
                'due_date' => $dueDate,
                'return_date' => $status === 'returned' ? $borrowDate->copy()->addDays(rand(7, 20)) : null,
                'status' => $status,
                'fine_amount' => $status === 'overdue' ? rand(5000, 50000) : null,
            ]);
        }
    }
}