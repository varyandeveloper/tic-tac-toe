<?php $this->extends('layout.php') ?>
<?php $this->section('content') ?>
<div class="container">
    <h2 class="page-heading text-success">Tic Tac Toe Game</h2>

    <div class="row">
        <div id="game"></div>
    </div>

    <div class="row">
        <h3 class="text-success text-center text-bold" id="message_area"></h3>
    </div>
</div>
<?php $this->endSection() ?>