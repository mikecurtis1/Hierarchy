# Hierarchy

A PHP application to generate a hierarchy of items according to descriptor tags.

$rows = file('rs_500_albums.csv');

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

print_r($t->getTagCounts());

Array
(
    [Rock] => 351
    [Pop Rock] => 87
    [Funk/Soul] => 80
    [Pop] => 54
    [Classic Rock] => 53
	...

print_r($t->getTagRanks());

Array
(
    [Rock] => 1
    [Pop Rock] => 2
    [Funk/Soul] => 3
    [Pop] => 4
    [Classic Rock] => 5
    [Country] => 6
	...

print_r($t->getTagSets());

    [#19. Astral Weeks. (Van Morrison)] => Array
        (
            [name] => #19. Astral Weeks. (Van Morrison)
            [uri] => https://en.wikipedia.org/w/index.php?search=Astral+Weeks.+%28Van+Morrison%29
            [delimited_tags] => JazzRockBluesFolkWorldCountryAcousticClassic RockFree Improvisation
            [tag_path] => RockClassic RockCountryFolkBluesWorldJazzAcoustic
        )
		
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
