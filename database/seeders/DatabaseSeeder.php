<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Like;
use App\Models\Message;
use App\Models\Post;
use App\Models\SocialNotification;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $defaultPassword = Hash::make('password123');

        $usersData = [
            [
                'name' => 'Muhamad Ferdiansyah',
                'username' => 'ferdiadit12',
                'email' => 'ferdi@example.com',
                'password' => $defaultPassword,
                'role' => 'user',
                'avatar' => '5f202477543ca.jpg',
                'hobby' => 'Art, Design, Programming',
                'city' => 'Jakarta, Indonesia',
                'born_date' => '2000-12-22',
            ],
            [
                'name' => 'Admin Management System',
                'username' => 'useradmin',
                'email' => 'admin@example.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'avatar' => 'app_icon.png',
                'hobby' => 'System Administration',
                'city' => 'Jakarta, Indonesia',
                'born_date' => '1998-01-01',
            ],
            [
                'name' => 'Akun User (Developer)',
                'username' => 'user123',
                'email' => 'user123@example.com',
                'password' => $defaultPassword,
                'role' => 'user',
                'avatar' => 'app_icon.png',
                'hobby' => 'Software Testing',
                'city' => 'Bandung, Indonesia',
                'born_date' => '2001-05-15',
            ],
            [
                'name' => 'Stevanus Ide Anggitya',
                'username' => 'stevanusidea',
                'email' => 'stevanus@example.com',
                'password' => $defaultPassword,
                'role' => 'user',
                'avatar' => '5f2247fe115e5.jpg',
                'hobby' => 'Digital Drawing, Gaming',
                'city' => 'Yogyakarta, Indonesia',
                'born_date' => '2000-08-24',
            ],
            [
                'name' => 'Maulalwi',
                'username' => 'maulalwi',
                'email' => 'maulalwi@example.com',
                'password' => $defaultPassword,
                'role' => 'user',
                'avatar' => 'app_icon.png',
                'hobby' => 'Reading & Coding',
                'city' => 'Surabaya, Indonesia',
                'born_date' => '2001-03-10',
            ],
            [
                'name' => 'DeadlyShooter',
                'username' => 'deadlyshooter',
                'email' => 'deadlyshooter@example.com',
                'password' => $defaultPassword,
                'role' => 'user',
                'avatar' => '5f22518e25f93.png',
                'hobby' => 'PUBG & Esport',
                'city' => 'Jakarta, Indonesia',
                'born_date' => '2002-11-04',
            ],
            [
                'name' => 'Ryan',
                'username' => 'ryan',
                'email' => 'ryan@example.com',
                'password' => $defaultPassword,
                'role' => 'user',
                'avatar' => 'app_icon.png',
                'hobby' => 'Traveling',
                'city' => 'Semarang, Indonesia',
                'born_date' => '2001-09-12',
            ],
        ];

        $users = [];
        foreach ($usersData as $data) {
            $user = User::updateOrCreate(['username' => $data['username']], $data);
            $users[$data['username']] = $user;
        }

        // Seed posts
        $postsData = [
            [
                'username' => 'ferdiadit12',
                'code' => 'HMvnqsPWigjiqWEnJGI5',
                'content' => 'Postingan Pertama.. Tampilan UI untuk aplikasi musik player Saya... gimana menurut kalian ??',
                'image' => '5f1fe7a4c8a91.png',
            ],
            [
                'username' => 'ferdiadit12',
                'code' => 'GeLE4N0C1nznmGCBDbky',
                'content' => 'Halo Semua.. SELAMAT DATANG DI SOSIAL MEDIA PERTAMA SAYA! Saya harap kalian suka dengan apa yang saya ciptakan saat ini, happy gibah silahkan ramaikann ^_^',
                'image' => null,
            ],
            [
                'username' => 'useradmin',
                'code' => 's4ZUnb4o5i7BksDNBMdM',
                'content' => 'Hallo Semua, saya User Database/Admin Management. Selamat datang di platform sosial media baru!',
                'image' => '5f1fe97255b10.jpg',
            ],
            [
                'username' => 'stevanusidea',
                'code' => 'bJCV3aqTQx4UfEI2L1h2',
                'content' => 'No copyright PNG artwork haha, karya iseng sore ini :)',
                'image' => '5f22491450c44.png',
            ],
            [
                'username' => 'deadlyshooter',
                'code' => 'pIm7cZWUY50GbnqqlaRJ',
                'content' => 'Deadly Shooter ID (PUBG Championship Highlight)',
                'image' => '5f22531a8f601.png',
            ],
            [
                'username' => 'ferdiadit12',
                'code' => 'D0KTJFlLEAQkl266vE7K',
                'content' => 'Bladient iOS13 UI Concept & Wallpaper preview',
                'image' => '5f2301387ae0c.png',
            ],
        ];

        $posts = [];
        foreach ($postsData as $p) {
            if (isset($users[$p['username']])) {
                $post = Post::updateOrCreate(
                    ['code' => $p['code']],
                    [
                        'user_id' => $users[$p['username']]->id,
                        'content' => $p['content'],
                        'image' => $p['image'],
                    ]
                );
                $posts[$p['code']] = $post;
            }
        }

        // Seed comments
        $commentsData = [
            [
                'post_code' => 'HMvnqsPWigjiqWEnJGI5',
                'username' => 'stevanusidea',
                'content' => 'Keren banget UI musik playernya bro! Clean dan modern.',
                'image' => null,
            ],
            [
                'post_code' => 'HMvnqsPWigjiqWEnJGI5',
                'username' => 'user123',
                'content' => 'Lumayan mantap gan, lanjutkan!',
                'image' => null,
            ],
            [
                'post_code' => 'GeLE4N0C1nznmGCBDbky',
                'username' => 'maulalwi',
                'content' => 'Mantap min, semoga makin rame aplikasinya!',
                'image' => null,
            ],
            [
                'post_code' => 'GeLE4N0C1nznmGCBDbky',
                'username' => 'user123',
                'content' => 'Biar makin semangat kembangin web ini!',
                'image' => '5f1fe8a697a29.jpg',
            ],
            [
                'post_code' => 'bJCV3aqTQx4UfEI2L1h2',
                'username' => 'ferdiadit12',
                'content' => 'Bagus gambarnya bro haha mantul!',
                'image' => null,
            ],
        ];

        foreach ($commentsData as $c) {
            if (isset($posts[$c['post_code']]) && isset($users[$c['username']])) {
                Comment::create([
                    'post_id' => $posts[$c['post_code']]->id,
                    'user_id' => $users[$c['username']]->id,
                    'content' => $c['content'],
                    'image' => $c['image'],
                ]);
            }
        }

        // Seed some likes
        if (isset($posts['HMvnqsPWigjiqWEnJGI5'])) {
            Like::firstOrCreate(['post_id' => $posts['HMvnqsPWigjiqWEnJGI5']->id, 'user_id' => $users['stevanusidea']->id]);
            Like::firstOrCreate(['post_id' => $posts['HMvnqsPWigjiqWEnJGI5']->id, 'user_id' => $users['user123']->id]);
            Like::firstOrCreate(['post_id' => $posts['HMvnqsPWigjiqWEnJGI5']->id, 'user_id' => $users['maulalwi']->id]);
        }
        if (isset($posts['GeLE4N0C1nznmGCBDbky'])) {
            Like::firstOrCreate(['post_id' => $posts['GeLE4N0C1nznmGCBDbky']->id, 'user_id' => $users['ferdiadit12']->id]);
            Like::firstOrCreate(['post_id' => $posts['GeLE4N0C1nznmGCBDbky']->id, 'user_id' => $users['deadlyshooter']->id]);
        }

        // Seed sample Messages
        Message::create([
            'sender_id' => $users['stevanusidea']->id,
            'receiver_id' => $users['ferdiadit12']->id,
            'content' => 'Halo Ferdi! Gimana progres tampilan aplikasi barunya?',
            'is_read' => true,
        ]);
        Message::create([
            'sender_id' => $users['ferdiadit12']->id,
            'receiver_id' => $users['stevanusidea']->id,
            'content' => 'Halo Vanus! Sudah di-upgrade ke Laravel 13, responsive, dan ada dark mode otomatis!',
            'is_read' => true,
        ]);
        Message::create([
            'sender_id' => $users['stevanusidea']->id,
            'receiver_id' => $users['ferdiadit12']->id,
            'content' => 'Mantap banget bro! Keren pol styling Tailwind-nya.',
            'is_read' => false,
        ]);

        // Seed sample Notifications
        SocialNotification::create([
            'user_id' => $users['ferdiadit12']->id,
            'actor_id' => $users['stevanusidea']->id,
            'type' => 'like',
            'title' => 'Suka Baru',
            'message' => 'Stevanus Ide Anggitya menyukai postingan Anda.',
            'link' => route('posts.show', 'HMvnqsPWigjiqWEnJGI5'),
            'is_read' => false,
        ]);
        SocialNotification::create([
            'user_id' => $users['ferdiadit12']->id,
            'actor_id' => $users['user123']->id,
            'type' => 'comment',
            'title' => 'Komentar Baru',
            'message' => 'Akun User (Developer) mengomentari postingan Anda.',
            'link' => route('posts.show', 'HMvnqsPWigjiqWEnJGI5'),
            'is_read' => false,
        ]);
        SocialNotification::create([
            'user_id' => $users['ferdiadit12']->id,
            'actor_id' => $users['stevanusidea']->id,
            'type' => 'message',
            'title' => 'Pesan Baru',
            'message' => 'Stevanus Ide Anggitya: "Mantap banget bro! Keren pol..."',
            'link' => route('messages.show', 'stevanusidea'),
            'is_read' => false,
        ]);
    }
}
