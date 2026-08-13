<?php

namespace App\Enums;

enum NewsCategory: string
{
    case News = 'news';
    case Press = 'press';
    case Update = 'update';
    case Event = 'event';
    case Project = 'project';

    public function label(): string
    {
        return match ($this) {
            self::News => 'Latest News',
            self::Press => 'Press Release',
            self::Update => 'Company Update',
            self::Event => 'Event',
            self::Project => 'Project',
        };
    }
}
