<?php

namespace Tests\Unit\App\Models;

use Illuminate\Database\Eloquent\Model;
use PHPUnit\Framework\TestCase;

abstract class ModelTestCase extends TestCase
{
    abstract protected function model(): Model;

    abstract protected function traits(): array;

    abstract protected function fillables(): array;

    abstract protected function casts(): array;

    public function testIfUseTraits()
    {
        $expectedsTraits = $this->traits();
        #Retorna array com as traits utilizadas pelo model Category
        $traitsUsed = array_keys(class_uses($this->model()));

        $this->assertEquals($expectedsTraits, $traitsUsed);
    }

    public function testFillables()
    {
        $expectedsFillables = $this->fillables();

        $fillables = $this->model()->getFillable();

        $this->assertEquals($expectedsFillables, $fillables);
    }

    /**
     * Testa se tem o atributo public $incrementing = false
     */
    public function testIncrementingIsFalse()
    {
        $model = $this->model();
        $this->assertFalse($model->incrementing);
    }

    public function testHasCasts()
    {
        $expectedsCast = $this->casts();
        $casts = $this->model()->getCasts();

        $this->assertEquals($expectedsCast, $casts);
    }
}
