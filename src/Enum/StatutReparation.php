<?php

namespace App\Enum;

enum StatutReparation: string
{
    case EN_ATTENTE = 'En attente';
    case EN_COURS = 'En cours';
    case REPAREE = 'Réparée';
    case IRREPARABLE = 'Irréparable';
}

