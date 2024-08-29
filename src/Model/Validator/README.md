# Валидатор

Компонент валидирует объекты согласно правилам валидации в атрибутах класса.

Для понимания, посмотрите на код:

```php
use Symfony\Component\Validator\Constraints as Assert;

class Post
{

    #[Assert\NotBlank()]
    #[Assert\Positive()]
    private int|string $id;

    #[Assert\NotBlank()]
    #[Assert\Length(min: 3, max: 10)]
    private string $title;
}
```
