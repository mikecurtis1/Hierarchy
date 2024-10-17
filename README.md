# Hierarchy

A PHP application to generate a hierarchy of items from arbitrary item descriptor tags.

## Sample Data File

`$rows = file('rs_500_albums.csv');`

```
Number,Year,Album,Artist,Genre,Subgenre
1,1967,Sgt. Pepper's Lonely Hearts Club Band,The Beatles,Rock,"Rock & Roll, Psychedelic Rock"
2,1966,Pet Sounds,The Beach Boys,Rock,"Pop Rock, Psychedelic Rock"
3,1966,Revolver,The Beatles,Rock,"Psychedelic Rock, Pop Rock"
4,1965,Highway 61 Revisited,Bob Dylan,Rock,"Folk Rock, Blues Rock"
5,1965,Rubber Soul,The Beatles,"Rock, Pop",Pop Rock
6,1971,What's Going On,Marvin Gaye,Funk/Soul,Soul
7,1972,Exile on Main St.,The Rolling Stones,Rock,"Blues Rock, Rock & Roll, Classic Rock"
8,1979,London Calling,The Clash,Rock,"Punk, New Wave"
9,1966,Blonde on Blonde,Bob Dylan,"Rock, Blues","Folk Rock, Rhythm & Blues"
10,1968,"The Beatles (""The White Album"")",The Beatles,Rock,"Rock & Roll, Pop Rock, Psychedelic Rock, Experimental"
...
```

### Generating Internal Descriptor Tags

In the sample file above, item rows are not ordered by a hierarchy of descriptors. The Hierarchy application will attempt to do so. Genre and subgenre terms from the data file are used to generate descriptor tags for each item row. The occurrences of each tag in the entire data file are totalled.

`print_r($t->getTagCounts());`

```
Array
(
    [Rock] => 351
    [Pop Rock] => 87
    [Funk/Soul] => 80
    [Pop] => 54
    [Classic Rock] => 53
	...
```

Tags are then ranked by frequency of occurrence.

`print_r($t->getTagRanks());`

```
Array
(
    [Rock] => 1
    [Pop Rock] => 2
    [Funk/Soul] => 3
    [Pop] => 4
    [Classic Rock] => 5
    [Country] => 6
	...
```

Tags are then hierarchically arranged by tag ranking. Higher ranking tags become top categories while lower ranking tags become subcategories. An item row's hierarchically ordered tags are set in the tag_path property.

In the following example, the delimited_tags property holds tag descriptors in their original un-ordered sequence. Tag_path holds the hierarchically ordered tags. Both properties are internally delimited with the ASCII unit separator to avoid conflict with any print characters in the original data file.

When the data file is parsed name and uri properties can be assigned to each item row.

`print_r($t->getTagSets());`

```
    [#19. Astral Weeks. (Van Morrison)] => Array
        (
            [name] => #19. Astral Weeks. (Van Morrison)
            [uri] => https://en.wikipedia.org/w/index.php?search=Astral+Weeks.+%28Van+Morrison%29
            [delimited_tags] => JazzRockBluesFolkWorldCountryAcousticClassic RockFree Improvisation
            [tag_path] => RockClassic RockCountryFolkBluesWorldJazzAcoustic
        )
```

Below are the tag rankings of that particular item in descending order.	

```
Array
(
    [Rock] => 1
    [Classic Rock] => 5
    [Country] => 6
    [Folk] => 8
    [Blues] => 12
    [World] => 13
    [Jazz] => 21
    [Acoustic] => 28
```

### HTML Output Generates a Tree

Below is a sample slice from the hierarcical tree diagram for the Rolling Stone Top 500 Albums.

<ul>
	<li>
	<span class="collection">Rock</span>
	<ul>
		<span class="collection">Hard Rock</span>
		<ul>
			<li class="member">
				<a href="https://en.wikipedia.org/w/index.php?search=Back+in+Black.+%28AC%2FDC%29">#77. Back in Black. (AC/DC)</a>
			</li>
			<li class="member">
				<a href="https://en.wikipedia.org/w/index.php?search=Highway+to+Hell.+%28AC%2FDC%29">#200. Highway to Hell. (AC/DC)</a>
			</li>
			<li class="member">
				<a href="https://en.wikipedia.org/w/index.php?search=Berlin.+%28Lou+Reed%29">#344. Berlin. (Lou Reed)</a>
			</li>
			<li class="member">
				<a href="https://en.wikipedia.org/w/index.php?search=Van+Halen.+%28Van+Halen%29">#415. Van Halen. (Van Halen)</a>
			</li>
			<li class="member">
				<a href="https://en.wikipedia.org/w/index.php?search=Destroyer.+%28KISS%29">#489. Destroyer. (KISS)</a>
			</li>
			<li>
			<span class="collection">Heavy Metal</span>
			<ul>
				<li class="member">
					<a href="https://en.wikipedia.org/w/index.php?search=Appetite+for+Destruction.+%28Guns+N%27+Roses%29">#62. Appetite for Destruction. (Guns N' Roses)</a>
				</li>
				<li class="member">
					<a href="https://en.wikipedia.org/w/index.php?search=Paranoid.+%28Black+Sabbath%29">#131. Paranoid. (Black Sabbath)</a>
				</li>
				<li class="member">
					<a href="https://en.wikipedia.org/w/index.php?search=Master+of+Reality.+%28Black+Sabbath%29">#300. Master of Reality. (Black Sabbath)</a>
				</li>
			</ul>
			</li>
			<li>
			<span class="COLL:Punk">Punk</span>
			<ul>
				<li>
				<span class="collection">Garage Rock</span>
				<ul>
					<li class="member">
						<a href="https://en.wikipedia.org/w/index.php?search=Raw+Power.+%28Iggy+and+The+Stooges%29">#128. Raw Power. (Iggy and The Stooges)</a>
					</li>
				</ul>
				</li>
			</ul>
			</li>
			<li>
			<span class="collection">Glam</span>
			<ul>
				<li class="member">
					<a href="https://en.wikipedia.org/w/index.php?search=Alive%21.+%28KISS%29">#159. Alive!. (KISS)</a>
				</li>
			</ul>
			</li>
			<li>
			<span class="collection">Arena Rock</span>
			<ul>
				<li class="member">
					<a href="https://en.wikipedia.org/w/index.php?search=Rust+Never+Sleeps.+%28Neil+Young+%26+Crazy+Horse%29">#351. Rust Never Sleeps. (Neil Young & Crazy Horse)</a>
				</li>
				<li class="member">
					<a href="https://en.wikipedia.org/w/index.php?search=Hysteria.+%28Def+Leppard%29">#464. Hysteria. (Def Leppard)</a>
				</li>
			</ul>
			</li>
		</ul>
		</li>
	</ul>
	</li>
</ul>

```
├───Rock
│   │
│   ├───Hard Rock
│   ├───#77. Back in Black. (AC/DC)
│   ├───#200. Highway to Hell. (AC/DC)
│   ├───#344. Berlin. (Lou Reed)
│   ├───#415. Van Halen. (Van Halen)
│   ├───#489. Destroyer. (KISS)
│   │   │
│   │   ├───Heavy Metal
│   │   ├───#62. Appetite for Destruction. (Guns N' Roses)
│   │   ├───#131. Paranoid. (Black Sabbath)
│   │   ├───#300. Master of Reality. (Black Sabbath)
│   │   │
│   │   ├───Punk
│   │   │   ├───Garage Rock
│   │   │   ├───#128. Raw Power. (Iggy and The Stooges)
│   │   │
│   │   ├───Glam
│   │   ├───#159. Alive!. (KISS)
│   │   │
│   │   ├───Arena Rock
│   │   ├───#351. Rust Never Sleeps. (Neil Young & Crazy Horse)
│   │   ├───#464. Hysteria. (Def Leppard)
│   │   │
```
