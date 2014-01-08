Yii pipe behavior
=================

Pipe owner`s method to allow more chained call style.

For example owner has method *gimmeAll*, that returns array that we want to transform by another owner`s method,
let it be *toSomething*. In old style we call:

    $bla = Something::create()->toSomething(Something::create()->one()->two()->three()->gimmeAll());

But with this behavior we can do this in more elegant way:

    $bla = Something::create()->one()->two()->three()->pipe('gimmeAll')->unpipe('toSomething', '{r}');

If unpiped method has single parameter, then we can omit '{r}' parameter and call it like:

    $bla = Something::create()->one()->two()->three()->pipe('gimmeAll')->unpipe('toSomething');


Category
--------

Syntactic sugar for fanats of method chaining.
See http://en.wikipedia.org/wiki/Method_chaining
