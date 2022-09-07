drop table if exists watches;
drop table if exists reviews;

create table watches (    
    id integer not null primary key autoincrement, 
    make varchar(30) not null,   
    model varchar(80) not null,    
    details text default 'Details missing.'
); 


create table reviews (    
    id integer not null primary key autoincrement, 
    user varchar(30) not null,   
    rating int(1) not null,    
    reviewText text not null,
    watchId integer not null references watches(id)
); 

insert into watches values (null, "Omega",  "Seamaster", "Quartz. Blue dial, water resistant to 300m. 4 year battery life.");
insert into watches values (null, "Omega", "Constellation", "Pie Pan. 1968-model, Automatic. Silver dial with steel case.");
insert into watches values (null, "Seiko",  "SKX007K2", "Black dial, water resistant to 200m. Automatic, on bracelet.");
insert into watches values (null, "Seiko", "SARB017J", "Made in Japan. Green dial, automatic, saphire crystal.");
insert into watches values (null, "Rolex", "Explorer II", "Polar, white dial. Stainless steel construction.");
insert into watches values (null, "Orient",  "Kamasu Diver", "Automatic movement, 42mm stainless steel case. Sapphire Crystal.");
insert into watches values (null, "Seiko", "Samurai SRPB51", "Automatic movement, 44mm stainless steel Case. Nice automatic movement featuring hand winding and hacking.");
insert into watches values (null, "Orient", "Bambino", "Classic entry level dress watch. Very inexpensive, perfect for anyone.");
insert into watches values (null, "Marathon", "TSAR", "Very robust quartz movement diving-style watch. Worn by government agencies, law enforcement and military forces around the world.");
insert into watches values (null, "Breitling", "Navitimer", "Classic and timeless chronograph.");
insert into watches values (null, "Jaeger LeCoultre", "Reverso", "Very sophisticated and beautiful watch. Rectanguar case, which may also be flipped, or 'reversed', revealing a different watch face on the other side.");
insert into watches values (null, "Omega", "Speedmaster", "The first watch on the moon! lassic and timeless chronograph famously issued to the NASA astronaughts during the space-race.");



insert into reviews values (null, "Ben",  "4", "Pretty good watch.", 3);
insert into reviews values (null, "Thomas", "3", "Decent watch, not the best I have seen.", 3);
insert into reviews values (null, "Peter",  "5", "Amazing watch! I love it!", 1);
insert into reviews values (null, "Bob", "5", "Great watch! Perfect for everyday use.", 1);
insert into reviews values (null, "Lucy", "4", "Nice watch, good value for money.", 5);
insert into reviews values (null, "Ben",  "5", "Best watch I have ever had! Never failed me in 8 years.", 1);
insert into reviews values (null, "Thomas", "3", "It is okey. It is a little too expensive.", 12);
insert into reviews values (null, "Jakkie",  "5", "Marvelous! Such cool history as well!", 12);
insert into reviews values (null, "John", "4", "Very very rugged watch. Super solid, but maybe a little too big.", 9);
insert into reviews values (null, "Harry", "3", "Alright watch. It's a little cheap-feeling, but it is also very inexpensive. To be expected, I guess. Good looks though!", 8);
insert into reviews values (null, "Charles",  "5", "My dream watch. Everything about it is perfect!", 5);
insert into reviews values (null, "Jake", "3", "I think it is over-hyped. Yes it looks cool, but it is simply too expensive.", 5);
insert into reviews values (null, "Peter",  "4", "Old watch, but I love it. It is a little inaccurate, but that is to be expected for a watch of this age. Looks great!", 2);
insert into reviews values (null, "Bob", "4", "Nicknamed the 'Alpinist', this is a watch rooted in exploration. Love the green dial.", 4);
insert into reviews values (null, "Lucy", "4", "Been around for ages, and I still think it looks perfect. Comfortable on the wrist, although it is very large.", 10);