<?php
enum Privilegios: int
{
    case User = 1;
    case Admin = 2;
    case Master = 4;

    function get(): int
    {
        return match ($this) {
            static::User => 1,
            static::Admin => 2,                                                                                             
            static::Master => 4
        };
    }
}
/**
 * Para usarlo se debe comparar mediante el simbolo & e igualar al privilegio
 * Ejemplo:
 * if (Privilegios::User->get() & numeritodelusuario) == Privilegios::User->get()) {
 *  codigo si es verdadero
 * }
 */