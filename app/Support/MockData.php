<?php

namespace App\Support;

class MockData
{
    public static function stats(): array
    {
        return [
            ['label' => 'Total Users',        'value' => 1284, 'delta' => '+12.4%', 'trend' => 'up',   'tone' => 'indigo',  'icon' => 'users',      'sub' => 'active accounts'],
            ['label' => 'Open Complaints',    'value' => 37,   'delta' => '-4.1%',  'trend' => 'down', 'tone' => 'coral',   'icon' => 'flag',       'sub' => 'awaiting response'],
            ['label' => 'Maintenance Jobs',   'value' => 18,   'delta' => '+2',     'trend' => 'up',   'tone' => 'teal',    'icon' => 'wrench',     'sub' => 'in progress'],
            ['label' => 'Rota Pending',       'value' => 9,    'delta' => 'today',  'trend' => 'flat', 'tone' => 'pink',    'icon' => 'calendar',   'sub' => 'needs approval'],
            ['label' => 'SOP Pending',        'value' => 6,    'delta' => 'new',    'trend' => 'flat', 'tone' => 'amber',   'icon' => 'book',       'sub' => 'awaiting review'],
            ['label' => 'Blog Posts',         'value' => 142,  'delta' => '+5 wk',  'trend' => 'up',   'tone' => 'sky',     'icon' => 'pencil',     'sub' => 'published'],
        ];
    }

    public static function complaints(): array
    {
        return [
            ['id' => 'C-8821', 'user' => 'Priya Shah',     'subject' => 'Broken heater in Flat 3B',        'priority' => 'High',   'status' => 'Open',       'time' => '12m ago'],
            ['id' => 'C-8820', 'user' => 'Marcus Lee',     'subject' => 'Noise complaint — upstairs',     'priority' => 'Medium', 'status' => 'Open',       'time' => '1h ago'],
            ['id' => 'C-8819', 'user' => 'Sofia Romano',   'subject' => 'Lift out of service (Block A)',  'priority' => 'High',   'status' => 'Escalated',  'time' => '3h ago'],
            ['id' => 'C-8818', 'user' => 'Ahmed Khan',     'subject' => 'Leaky kitchen tap',               'priority' => 'Low',    'status' => 'Open',       'time' => '5h ago'],
            ['id' => 'C-8817', 'user' => 'Jenna Willis',   'subject' => 'Parcel not delivered',            'priority' => 'Medium', 'status' => 'In Review',  'time' => 'Yesterday'],
        ];
    }

    public static function maintenance(): array
    {
        return [
            ['id' => 'M-4431', 'job' => 'Replace boiler — Flat 7A',        'assignee' => 'Tom Rivers',  'due' => 'Today',   'status' => 'In Progress'],
            ['id' => 'M-4430', 'job' => 'Communal lighting — Block C',     'assignee' => 'Nia Okafor',  'due' => 'Tomorrow','status' => 'Scheduled'],
            ['id' => 'M-4429', 'job' => 'Garden hedge trim',                'assignee' => 'Dan Miles',   'due' => 'Fri',     'status' => 'Scheduled'],
            ['id' => 'M-4428', 'job' => 'CCTV camera fault — entrance',     'assignee' => 'Li Wei',      'due' => 'Today',   'status' => 'Urgent'],
            ['id' => 'M-4427', 'job' => 'Fire alarm panel check',           'assignee' => 'Tom Rivers',  'due' => 'Mon',     'status' => 'Scheduled'],
        ];
    }

    public static function rotaApprovals(): array
    {
        return [
            ['id' => 'R-220', 'team' => 'Reception',   'shift' => 'Mon 08:00 – 16:00', 'submitted_by' => 'Grace Hall',  'time' => '30m ago'],
            ['id' => 'R-219', 'team' => 'Maintenance', 'shift' => 'Tue 06:00 – 14:00', 'submitted_by' => 'Tom Rivers',  'time' => '1h ago'],
            ['id' => 'R-218', 'team' => 'Security',    'shift' => 'Wed 22:00 – 06:00', 'submitted_by' => 'Oscar Paz',   'time' => '2h ago'],
            ['id' => 'R-217', 'team' => 'Cleaning',    'shift' => 'Thu 09:00 – 13:00', 'submitted_by' => 'Ruth Bamidele','time' => '4h ago'],
        ];
    }

    public static function sopApprovals(): array
    {
        return [
            ['id' => 'S-77', 'title' => 'Fire evacuation v4',             'author' => 'H&S Team',      'version' => '4.0', 'time' => '1d ago'],
            ['id' => 'S-76', 'title' => 'Visitor sign-in procedure',      'author' => 'Reception',     'version' => '2.1', 'time' => '2d ago'],
            ['id' => 'S-75', 'title' => 'Hot-water system isolation',     'author' => 'Maintenance',   'version' => '1.3', 'time' => '3d ago'],
            ['id' => 'S-74', 'title' => 'Complaint handling flow',        'author' => 'Ops',           'version' => '2.0', 'time' => '4d ago'],
        ];
    }

    public static function recentUsers(): array
    {
        return [
            ['name' => 'Elena Marquez',  'email' => 'elena.m@northpark.co',   'role' => 'Resident',   'joined' => '2h ago',   'initials' => 'EM', 'tone' => 'indigo'],
            ['name' => 'Jamal Odu',      'email' => 'jamal.o@northpark.co',   'role' => 'Staff',      'joined' => '5h ago',   'initials' => 'JO', 'tone' => 'coral'],
            ['name' => 'Kiera Osborne',  'email' => 'kiera.os@northpark.co',  'role' => 'Resident',   'joined' => 'Yesterday','initials' => 'KO', 'tone' => 'teal'],
            ['name' => 'Rohan Patel',    'email' => 'rohan.p@northpark.co',   'role' => 'Contractor', 'joined' => 'Yesterday','initials' => 'RP', 'tone' => 'pink'],
            ['name' => 'Mei Tanaka',     'email' => 'mei.t@northpark.co',     'role' => 'Admin',      'joined' => '2d ago',   'initials' => 'MT', 'tone' => 'amber'],
        ];
    }

    public static function recentPosts(): array
    {
        return [
            ['title' => 'Winter maintenance schedule released',        'author' => 'Mei Tanaka',  'category' => 'News',        'status' => 'Published', 'time' => '1h ago'],
            ['title' => 'Community garden opens this Saturday',        'author' => 'Grace Hall',  'category' => 'Blog',        'status' => 'Published', 'time' => '6h ago'],
            ['title' => 'New contractor onboarding policy (draft)',    'author' => 'Ops',         'category' => 'Policy',      'status' => 'Draft',     'time' => 'Yesterday'],
            ['title' => 'Fire drill — Tuesday 10:00 AM',              'author' => 'H&S Team',    'category' => 'Announcement','status' => 'Published', 'time' => '2d ago'],
            ['title' => 'Q1 resident satisfaction report',             'author' => 'Mei Tanaka',  'category' => 'Report',      'status' => 'Published', 'time' => '3d ago'],
        ];
    }

    public static function allUsers(): array
    {
        return array_merge(self::recentUsers(), [
            ['name' => 'Bruno Costa',    'email' => 'bruno.c@northpark.co',   'role' => 'Resident',   'joined' => '1w ago',  'initials' => 'BC', 'tone' => 'sky'],
            ['name' => 'Ana Silva',      'email' => 'ana.s@northpark.co',     'role' => 'Staff',      'joined' => '2w ago',  'initials' => 'AS', 'tone' => 'indigo'],
            ['name' => 'Hiro Sato',      'email' => 'hiro.s@northpark.co',    'role' => 'Contractor', 'joined' => '3w ago',  'initials' => 'HS', 'tone' => 'teal'],
        ]);
    }
}
