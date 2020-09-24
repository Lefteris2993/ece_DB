drop database if exists superMarket;
create database superMarket;

create table market
(
    marketID int not null auto_increment,
    address int,
    street varchar(50)  CHECK  (NOT(street REGEXP '[0-9]')),
    workingHours varchar(11) DEFAULT '08:00-21:00',
    postalCode  char(5) check (length(postalCode)=5),
    sqrMeters int,
    city varchar(50)  CHECK  (NOT(city REGEXP '[0-9]')),
    primary key(marketID)
)ENGINE = InnoDB;

create table category
(
    categoryID int not null auto_increment,
    catType varchar(50) not null CHECK  (NOT(catType REGEXP '[0-9]')),
    primary key(categoryID)
)ENGINE = InnoDB;

create table offers
(
    marketID int not NULL,
    categoryID int not null,
    primary key (marketID, categoryID),
    constraint foreign key (marketID)
        REFERENCES market (marketID)
        on delete cascade on update cascade,
    constraint FOREIGN key (categoryID)
        REFERENCES category (categoryID) 
        on delete cascade on update cascade
)ENGINE = InnoDB;

CREATE TABLE product 
(
  barcode INT NOT NULL check (length(barcode)=3),
  price float NOT NULL,
  labeled int check(labeled < 2 and labeled > -1),
  name varchar(50),
  categoryID int NOT NULL,
  PRIMARY KEY (barcode),
  CONSTRAINT FOREIGN KEY (categoryID)
    REFERENCES category (categoryID)
    on delete cascade on update cascade
)ENGINE = InnoDB;

create table isAt 
(
    marketID int not NULL,
    barcode int not NULL ,
    shelf int,
    corridor int,
    primary key (marketID, barcode),
    constraint FOREIGN key (marketID)
        REFERENCES market (marketID)
        on delete cascade on update cascade,
    constraint FOREIGN key (barcode)
        REFERENCES product (barcode)
        on DELETE CASCADE on UPDATE CASCADE
)ENGINE = InnoDB;

create table priceHistory
(
    startDate date not null,
    price float not null,
    barcode int not NULL ,
    PRIMARY KEY (startDate, barcode),
    CONSTRAINT FOREIGN KEY (barcode)
        REFERENCES product (barcode)
        on delete cascade
)ENGINE = InnoDB;

create table customer 
(
    cardID int not null auto_increment,
    points int DEFAULT 0,
    fullName varchar(50) not null CHECK  (NOT(fullName REGEXP '[0-9]')),
    gender varchar(50),
    birthDate date,
    married int check(married < 2 and married > -1),
    children int check (children > -1),
    address int,
    street varchar(50),
    postalCode  char(5) check (length(postalCode)=5),
    city varchar(50),
    PRIMARY key (cardID)
)ENGINE = InnoDB;

create table goesTo 
(
    cardID int not null,
    marketID int not null,
    primary key (cardID, marketID),
    CONSTRAINT FOREIGN key (cardID)
        REFERENCES customer (cardID)
        on delete cascade,
    constraint FOREIGN KEY (marketID)
        REFERENCES market (marketID)
        on delete CASCADE
)ENGINE = InnoDB;

create table purchase
(
    purchaseID int not null auto_increment,
    date_time datetime,
    paymentMethod varchar(50),
    totalCost float DEFAULT 0 ,
    points int DEFAULT 0,
    cardID int,
    marketID int not null,
    PRIMARY key (purchaseID),
    CONSTRAINT FOREIGN key (cardID)
        REFERENCES customer (cardID),
    CONSTRAINT FOREIGN key (marketID)
        REFERENCES market (marketID)
)ENGINE = InnoDB;

create table contains
(
    purchaseID int not null,
    barcode int not NULL ,
    quantity int DEFAULT 1 check(quantity > 0),
    PRIMARY KEY (purchaseID, barcode),
    CONSTRAINT FOREIGN KEY (barcode)
        REFERENCES product (barcode),
    CONSTRAINT FOREIGN key (purchaseID)
        REFERENCES purchase (purchaseID)
        on DELETE cascade
)ENGINE = InnoDB;

DELIMITER $$
CREATE TRIGGER make_priceHistory AFTER insert ON product FOR EACH ROW
begin
    insert into priceHistory(startDate, price, barcode)
    VALUES (CURDATE(), NEW.price, NEW.barcode);
end; $$
DELIMITER ;

DELIMITER $$
CREATE TRIGGER update_priceHistory AFTER UPDATE ON product FOR EACH ROW
begin
    IF (NEW.price != OLD.price) THEN
        insert into priceHistory(startDate, price, barcode)
        VALUES (CURDATE(), NEW.price, NEW.barcode);
    END if;
end; $$
DELIMITER ;

DELIMITER $$
CREATE TRIGGER update_points AFTER INSERT ON purchase FOR EACH ROW
BEGIN
    if (NEW.cardID IS NOT NULL) THEN
        UPDATE customer
        set customer.points = customer.points + NEW.points 
        WHERE customer.cardID = NEW.cardID;
    END IF;
END; $$
DELIMITER ;

DELIMITER $$
CREATE TRIGGER make_goesTo AFTER INSERT ON purchase FOR EACH ROW
BEGIN

   IF NOT EXISTS (SELECT * from goesTo where goesTo.cardID = new.cardID and goesTo.marketID = new.marketID ) then
      insert into goesTo(cardID, marketID)
      values (new.cardID, new.marketID);
   END if;

       
END; $$
DELIMITER ;

DELIMITER $$
CREATE TRIGGER update_goesTo AFTER UPDATE ON purchase FOR EACH ROW
begin
    if (NEW.marketID != OLD.marketID ) then
        update goesTo 
        set  goesTo.marketID = new.marketID
        where goesTo.marketID = old.marketID;
    end if;
    if (new.cardID != old.cardID) then
        update goesTo 
        set  goesTo.cardID = new.cardID
        where goesTo.cardID = old.cardID;
    end if;
end; $$
DELIMITER ;


DELIMITER $$
CREATE TRIGGER update_cost_points AFTER INSERT ON contains FOR EACH ROW
BEGIN
    set @a = (select price from product where product.barcode = new.barcode);
    UPDATE purchase
    SET purchase.totalCost = (purchase.totalCost + @a * new.quantity), purchase.points = (purchase.points + @a*new.quantity*3) 
    where purchase.purchaseID = new.purchaseID;

END; $$
DELIMITER ;


create index product_names
on product (name);

create index product_prices
on product (price);

create index category_types
on category (catType);


create view view_customers as
    select 
        *
    from
        customer
    order by cardID;





create view view_sales as 
    SELECT b.marketID, category.catType, b.qa from category, 
    (SELECT *, sum(quantity) over (partition by a.marketID, a.categoryID) as qa FROM
    (SELECT purchase.marketID, product.categoryID, contains.quantity  from contains, product, purchase
    WHERE contains.barcode = product.barcode and contains.purchaseID = purchase.purchaseID) as a) as b
    WHERE category.categoryID = b.categoryID
    group by marketID, catType
    order by marketID;
    