<?php
namespace App\Controllers {
    class BaseController { public $request; public $response; }
}
namespace App\Models {
    class TestModel {
        private bool $hasActiveCheck = false;
        public function where($field, $value) {
            if ($field === 'is_active' && $value === 1) {
                $this->hasActiveCheck = true;
            }
            return $this;
        }
        public function find($id) {
            if (!$this->hasActiveCheck) throw new \RuntimeException('Missing active-test restriction');
            return null;
        }
    }
}
namespace {
    function auth() { return new class { public function id() { return 12; } }; }
    require __DIR__ . '/../app/Controllers/AptitudeController.php';
    foreach ([
        [['test_id' => 1, 'mode' => 'official', 'employer_required' => 1], 403],
        [['test_id' => 1, 'mode' => 'invalid'], 400],
        [['test_id' => 1, 'mode' => 'official'], 404],
    ] as [$payload, $expected]) {
        $controller = new \App\Controllers\AptitudeController();
        $controller->request = new class($payload) {
            public function __construct(private array $payload) {}
            public function getPost($name) { return $this->payload[$name] ?? null; }
        };
        $controller->response = new class {
            public int $code = 200;
            public function setJSON($data) { return $this; }
            public function setStatusCode($code) { $this->code = $code; return $this; }
        };
        if ($controller->startAttempt()->code !== $expected) throw new RuntimeException('Assessment start restriction failed');
    }
    echo "Passed: forged employer flag, invalid mode and inactive/missing test rejected.\n";
}
