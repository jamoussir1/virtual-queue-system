<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Queue;
use App\Models\ServiceWindow;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        $admin = User::updateOrCreate([
            'email' => 'admin@esprit.tn',
        ], [
            'name'     => 'Admin ESPRIT',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);

        // Agents
        $agent1 = User::updateOrCreate([
            'email' => 'sami@esprit.tn',
        ], [
            'name'     => 'Agent Sami',
            'password' => Hash::make('password'),
            'role'     => 'agent',
        ]);
        $agent2 = User::updateOrCreate([
            'email' => 'nour@esprit.tn',
        ], [
            'name'     => 'Agent Nour',
            'password' => Hash::make('password'),
            'role'     => 'agent',
        ]);

        // Customers
        User::updateOrCreate([
            'email' => 'ines@mail.com',
        ], [
            'name'     => 'Ines Ben Fraj',
            'password' => Hash::make('password'),
            'role'     => 'customer',
        ]);
        User::updateOrCreate([
            'email' => 'ahmed@mail.com',
        ], [
            'name'     => 'Ahmed Ben Mansour',
            'password' => Hash::make('password'),
            'role'     => 'customer',
        ]);
        User::updateOrCreate([
            'email' => 'hajer@mail.com',
        ], [
            'name'     => 'Hajer Bouricha',
            'password' => Hash::make('password'),
            'role'     => 'customer',
        ]);

        // Queues
        $q1 = Queue::updateOrCreate([
            'name' => 'Customer Service',
        ], [
            'status'       => 'open',
            'max_capacity' => 50,
            'created_by'   => $admin->id,
        ]);
        $q2 = Queue::updateOrCreate([
            'name' => 'Technical Support',
        ], [
            'status'       => 'open',
            'max_capacity' => 30,
            'created_by'   => $admin->id,
        ]);
        $q3 = Queue::updateOrCreate([
            'name' => 'Billing',
        ], [
            'status'       => 'paused',
            'max_capacity' => 20,
            'created_by'   => $admin->id,
        ]);

        // Service Windows
        ServiceWindow::updateOrCreate([
            'label'    => 'Window A',
            'queue_id' => $q1->id,
        ], [
            'is_active' => true,
            'agent_id'  => $agent1->id,
        ]);
        ServiceWindow::updateOrCreate([
            'label'    => 'Window B',
            'queue_id' => $q1->id,
        ], [
            'is_active' => true,
            'agent_id'  => $agent2->id,
        ]);
        ServiceWindow::updateOrCreate([
            'label'    => 'Window C',
            'queue_id' => $q2->id,
        ], [
            'is_active' => false,
            'agent_id'  => null,
        ]);
    }
}
