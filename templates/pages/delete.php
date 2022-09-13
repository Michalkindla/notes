<div class="show">

<?php $note = $params['note'] ?? null;  ?>
<?php if($note) : ?>
<ul>
    <li>Id: <?php echo $note['id'];  ?></li>
    <li>Tytuł: <?php echo $note['title'];  ?></li>
    <li>Opis: <?php echo $note['description']; ?></li>
    <li>Zapisano: <?php echo $note['created']; ?></li>
</ul>
<form action="/php2/?action=delete" method="POST">
    <input type="hidden" name="id" value="<?php echo $note['id'] ?>">
    <button type="submit">Usuń</button>
</form>
<?php else: ?>
    <div>Brak notatki do wyświetlenia</div>
<?php endif; ?>
<a href="/php2/"><button>Powrót do listy notatek</button></a>
</div>