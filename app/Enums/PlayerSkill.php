<?php

namespace App\Enums;

enum PlayerSkill: string
{
    case DEFENSE = 'defense';
    case ATTACK = 'attack';
    case SPEED = 'speed';
    case STREGTH = 'strength';
    case STAMINA = 'stamina';
}