# Dekoboler

A work-in-progress CLI tool to extract highlights from my Kobo. Replaces my half-baked [Dekindler](https://github.com/mattstein/dekindler) now that I’ve changed reading devices.

It was a chance to play with [Laravel Zero](https://laravel-zero.com) and extract exactly what I’d like from the Kobo’s SQLite database.

Don’t get too excited, because this is pretty much the whole thing right now:

```
❯ php dekoboler browse

 Which book?:
  [0 ] Anything You Want: 40 lessons for a new kind of entrepreneur
  [1 ] Blood in the Machine
  [2 ] Clear Thinking
  [3 ] Hands of Time
  [4 ] How to Do Nothing
  [5 ] How to Live: 27 conflicting answers and one weird conclusion
  [6 ] Immortality
  [7 ] Julia
  [8 ] Parable of the Sower
  [9 ] Revolutionary Spring
  [10] Self-Compassion
  [11] Skinny Legs and All
  [12] The Adventures of Amina al-Sirafi
  [13] The Art of the Novel
  [14] The Artist's Way
  [15] The Book of Doors
  [16] The Internet Con: How to Seize the Means of Computation
  [17] The Left Hand of Darkness
  [18] The Murderbot Diaries
  [19] The Unbearable Lightness of Being
  [20] Ursula K. Le Guin: Five Novels (LOA #379)
  [21] What Feasts at Night
  [22] What's Our Problem?
  [23] Witch King
  [24] World Wide Waste: How Digital Is Killing Our Planet—and What We Can Do About It
 > 4

 What do you want to do with clippings?:
  [0] view
  [1] save
 > 0

---
title: How to Do Nothing
---

> What the tastes of neoliberal techno manifest–destiny and the culture of Trump have in common is impatience with anything nuanced, poetic, or less-than-obvious.

– 8/17/24 at 3:29am


> Sometimes it’s good to be stuck in the in-between, even if it’s uncomfortable.

– 8/17/24 at 3:34am


> One might say the parks and libraries of the self are always about to be turned into condos.

– 8/17/24 at 4:30am


> And it takes a break to remember that: a break to do nothing, to just listen, to remember in the deepest sense what, when, and where we are.

– 8/17/24 at 4:50am
```

## Why You Shouldn’t Care

- No tests.
- Assumes you’ve connected your Kobo to your Mac and allowed it to connect/mount.
- Prints or saves Markdown in a sparing format that’s hopelessly inflexible at the moment.
- See TODO.

## Life Hack

Run `php dekoboler copy-database` to make a copy of your Kobo’s SQLite database in the project’s `storage/app` directory.

You can then update `config/database.php` and point to the copy, and put your Kobo back where it normally lives:

```php
'database' => 'storage/app/KoboReader.sqlite',
```

## TODO

- [ ] make database read only!
- [ ] figure out where to get read time
- [ ] figure out how to approximate page numbers or locations (or chapters?)
- [ ] improve reliability of ePub metadata reading
- [ ] fetch StoryGraph and Literal book URLs
- [ ] fix timezone
