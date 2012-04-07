# History for Laravel


## What can we do with it?

In stead of directly contacting the database to do our changes, we send an Event, this Event gets stored in a EventLog and is sent over a Bus.
On the other side of the Bus, there can be Listeners, once they receive an event they Listened for they can execute some code (insert / update / delete something in the database)

Now, what is so cool about this?

We can Replay our EventLog, and add Different Listeners while we Replay it, this way, we could denormalize someting, store stuff in another DB and a lot more.
So when refactoring stuff, you don't have to make a migration that moves all your data to new tables, you just change the Listeners, clear your DB and Replay the log.

Awesome, right? thats not all...

Since you store every Action in your system, you can also Replay the log and find Patterns, so it's a great way to do data mining, when you have found a pattern that you would like to add to your DB,
Add a listener for it, Replay the log et voila...

I hope you guys will enjoy it!


```PHP
	return array(
		'es' => array(
			'auto' => true,
			'handles' => 'es',
			'location' => 'history/core'
		),
		'demo' => array(
			'auto' => true,
			'handles' => 'demo',
			'location' => 'history/demo'
		)
	);
```