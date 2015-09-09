<?php $name = $request->get('name', 'Иван Иввнов') ?>
Здравствуй, <?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>