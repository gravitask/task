# gravitask/task
A *serious*ly powerful library for working with TODO list items and tasks.

## Features
* Parse and format `TaskItem` objects to and from JSON, and
[todo.txt format](https://github.com/ginatrapani/todo.txt-cli/wiki/The-Todo.txt-Format).

## Example
```php
$parser = new Gravitask\Task\Parser\TodoTxtParser();

$input = "(A) Write the README file";
$task = $parser->parse($input);

$task->getPriority(); // Result: "A"
$task->getTask(); // Result: "Write the README file"
```

---

## TaskItem
This class is the "task object" and holds all of the information about the task,
such as its creation date, priority, description, etc.

### Constants
| Name               | Definition |
| ------------------ | ---------- |
| `STATUS_ACTIVE`    | The status of the task is active - i.e. in progress, **not** completed. |
| `STATUS_COMPLETED` | The status of the task is completed. |

### Methods
#### setTask($task)
Set the name/description of the task to be completed.

##### Example
```php
$taskItem->setTask("Make another coffee");
```

---

#### getTask()
Retrieve the name/description of the task.

##### Example
```php
$task->getTask();
// "Make another coffee"
```

---

#### setContexts(array $contexts)
Set the task's contexts to the items provided in the `$contexts` array.

##### Example
```php
$contexts = ['email', 'computer'];
$task->setContexts($contexts);
```

---

#### addContext($context)
Append a single context item to the pre-existing array of contexts.

##### Example
```php
$contexts = ['email'];
$task->setContexts($contexts);

$task->addContext('computer');
```

---

#### getContexts()
Retrieve an array of the task's contexts.

##### Example
```php
$contexts = ['email', 'computer'];
$task->setContexts($contexts);

$task->getContexts();
// ['email', 'computer']
```

---

#### setProjects(array $projects)
Set the task's projects to the items provided in the `$projects` array.

##### Example
```php
$projects = ['SecretProject'];
$task->setProjects($projects);
```

---

#### addProject($project)
Append a single project item to the pre-existing array of projects.

##### Example
```php
$projects = ['SecretProject'];
$task->setProjects($projects);

$task->addProject('Work');
```

---

#### getProjects()
Retrieve an array of the task's projects.

##### Example
```php
$projects = ['SecretProject'];

$task->setProjects($projects);
$task->addProject('Work');

$task->getProjects();
// ['SecretProject', 'Work']
```

---

#### setCreationDate(\DateTime $date)
Set the task's *optional* creation date.

The `$date` argument is a `DateTime` object set to the required date and time.

##### Example
```php
$creationDate = new \DateTime::createFromFormat("Y-m-d", "2016-08-16");
$task->setCreationDate($creaionDate);
```

---

#### getCreationDate()
Retrieve the *optional* creation date value for the task.

##### Example
```php
$task->getCreationDate();
// \DateTime object
```

---

#### setCompletionDate(\DateTime $date)
Set the date of when the task was completed.

The `$date` argument is a `DateTime` object set to the required date and time.

```php
$task->setStatus(TaskItem::STATUS_COMPLETED);
$completionDate = new \DateTime::createFromFormat("Y-m-d", "2016-08-20");
$task->setCompletionDate($completionDate);
```

---

#### getCompletionDate()
Retrieve the date that the task was completed.

##### Example
```php
$task->setStatus(TaskItem::STATUS_COMPLETED);
$completionDate = new \DateTime::createFromFormat("Y-m-d", "2016-08-20");
$task->setCompletionDate($completionDate);

$task->getCompletionDate()
// \DateTime object
```

---

#### setPriority($priority)
Set the task's priority to the provided uppercase single letter of the alphabet. `A`
signifies the highest priority, whilst `Z` represents the lowest.

##### Example
```php
$task->setPriority("B");
```

---

#### getPriority()
Retrieve the task's priority value represented by a single, uppercase letter of the
alphabet.

##### Example
```php
$task->setPriority("F");

$task->getPriority();
// "F"
```

---

#### setStatus($status)
Set the status of the task to a different value.

**Requirements**:
* You should **ONLY** use the values provided as `TaskItem` constants beginning with
  `STATUS_`.

##### Example
```php
$task->setStatus(TaskItem::STATUS_COMPLETED);
```

---

#### getStatus()
Retrieve the current status of the task. By default this value will be
`TaskItem::STATUS_ACTIVE`.

##### Example
```php
$task->setStatus(TaskItem::STATUS_COMPLETED);

$task->getStatus();
// Integer value representation of the status ENUM.
```

---

## Formatters
### Required Methods
> All formatters **MUST** implement the `Gravitask\Task\Formatter\FormatterInterface`.

#### format(TaskItem $taskItem)
Format the provided `TaskItem` using the preferred formatter class, e.g. `TodoTxtFormatter`.

##### Example
```php
$task->setPriority("A");
$task->setTask("Write example code");

$formatter = new Gravitask\Task\Formatter\TodoTxtFormatter();

$output = $formatter->format($task);
// "(A) Write example code"
```

---

## Parsers
### Required Methods
> All parsers **MUST** implement the `Gravitask\Task\Parser\ParserInterface`.

#### parse($input)
Parse the provided `$input` variable and return a `Gravitask\Task\TaskItem` object,
or `FALSE` on failure to parse.

##### Example
```php
$parser = new Gravitask\Task\Parser\TodoTxtParser();

$input = "(A) Write the README file";
$task = $parser->parse($input);

$task->getPriority(); // Result: "A"
$task->getTask(); // Result: "Write the README file"
```
