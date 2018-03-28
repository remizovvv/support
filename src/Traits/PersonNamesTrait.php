<?php

namespace Omadonex\Support\Traits;

trait PersonNamesTrait
{
    protected function getDefaultNameAttribute()
    {
        return 'Пользователь';
    }

    /**
     * Полное обращение к человеку (Имя Фамилия и Отчество)
     * @return string
     */
    public function getFullNameAttribute()
    {
        $str = trim($this->fname . ' ' . $this->sname . ' ' . $this->tname);

        return $str ?: $this->getDefaultNameAttribute();
    }

    /**
     * Короткое обращение к человеку (Имя и Фамилия)
     * @return string
     */
    public function getShortNameAttribute()
    {
        $str = trim($this->fname . ' ' . $this->sname);

        return $str ?: $this->getDefaultNameAttribute();
    }

    /**
     * Официальное обращение к человеку (Имя и Отчество)
     * @return string
     */
    public function getOfficialNameAttribute()
    {
        $str = trim($this->fname . ' ' . $this->tname);

        return $str ?: $this->getDefaultNameAttribute();
    }

    /**
     * Фамилия, инициалы
     * @return string
     */
    public function getInitialsNameAttribute()
    {
        $initials = '';
        if ($this->fname) {
            $initials .= $this->fname[0] . '.';
        }
        if ($this->tname) {
            $initials .= $this->tname[0] . '.';
        }
        $str = trim($this->sname . ' ' . $initials);

        return $str ?: $this->getDefaultNameAttribute();
    }
}
