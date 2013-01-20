<a href ="<?= $generator->generate( 'hello', array( 'name' => 'Oleg' ) ); ?>">Hello Oleg</a>
<br />
<a href ="<?= $generator->generate( 'bye' ); ?>">Bye</a>
<br />
Hello <?= htmlspecialchars( $name, ENT_QUOTES, 'UTF-8' ) ?>