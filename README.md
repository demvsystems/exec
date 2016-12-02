# exec

exec is a wrapper for the php ```exec()``` command, which allows to build CLI commands with the help of chaining.

## The Command Class
Everything starts with the Command class, which let's you create any command in the following fashion:

```php
echo Command::create()
  ->app('echo')
  ->input('Hello World')
  ->exec();
//prints out "Hello World"
```

This would create a command, which is running the application echo with the input "Hello World" and executes it right away. 
For a full reference on what is possible with the Command head over to the [associated wiki entry](https://github.com/demvsystems/exec/wiki/Command). 

## Asynchronous  Commands
You are also able to execute a command asynchronously, by appending the ```async()``` method to your command. The echo example from before would look like this:
```php
$async = Command::create()
  ->app('echo')
  ->input('Hello World')
  ->async()
  ->exec();
```
Which returns an ```Async``` object. With this object, you can do: 
- check if the command is still running
- check if a command with a similar syntax is currently running
- kill ithe command
- kill every command with a similar syntax

Of course the above example doesn't make much sense, because we will never get a look at the result. Therefor we can pipe the 
result into a file.
```php
$async = Command::create()
  ->app('echo')
  ->input('Hello World')
  ->async()
  ->path('hello.txt')
  ->exec();
```
This will write a file called ```hello.txt``` with the content of our command, as soon as it terminates. For more on asynchronous 
commands have a look at [the wiki page](https://github.com/demvsystems/exec/wiki/Async).

##Application
Instead of defining every app in a string, inside of the app call, you are also able to use some of the predefined apps, which 
wrap away some of the repetetive work. For example take the [PhpApp](https://github.com/demvsystems/exec/blob/master/src/Application/PhpApp.php). Normally you would build a command like this to get the 
PHP version:
```php
Command::create()
  ->app('php')
  ->arg('v')
  ->exec();
```

But with the PhpApp you can get rid of the string param:
```php
Command::create()
  ->phpApp()
  ->arg('v')
  ->exec();
```
There are already some other advanced apps, which wrap away their special syntax, like the [XArgsApp](https://github.com/demvsystems/exec/blob/master/src/Application/XargsApp.php).
For a full reference on which apps are available and how they work, have a look at the [Application wiki entry](https://github.com/demvsystems/exec/wiki/Application).

You are also able to write your own app wrappers. Have a look [at this](https://github.com/demvsystems/exec/wiki/Create-your-own-Application-Wrappers) on how to do it.
