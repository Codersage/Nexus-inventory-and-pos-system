/*SELECT c.* FROM (
	SELECT ROW_NUMBER() OVER(order  BY ProdName) AS RowID,*  FROM inventorysuppliers
    ) AS c  WHERE c.RowID > 1 AND c.RowID <=3  ;
    

use inventory
--user login sp
 alter proc ulogin @email varchar(30),@password varchar(30)
as
begin
 SELECT * from Users
where Email=  @email and Password_=@password
END  
 SELECT *  from users

 */
--register user sp
 alter proc register @fn varchar(30),@ln varchar(30),@em varchar(30),@ps varchar(30)
AS

BEGIN
insert  into Users (Fname,Lname,Email,Password_) VALUES (@fn,@ln,@em,@ps)
END


--insert inventory sp
create proc addinventory @pn varchar(30), @pr money, @qty int, @id varchar(32),  @spid varchar(32)
as
begin
insert into Inventory([ProdName]
      ,[price]
      ,[SpID]
      ,[Qty]
      ,[uid])  values (@pn,@pr,@spid,@qty,@id)
end
use inventory
select * from Supplier
exec addinventory 'chicken','300','3','US1','SP1'




--- insert supplier sp
create proc addsupplier @sn varchar(30),@sl varchar(30),@se varchar(30) ,@pn varchar(12),@id varchar(32)
as
begin
insert into Supplier values(@sn,@sl,@se,@pn,@id)
end

exec addsupplier 'redstripe','spur tree p.o.','redstripe@outlook.com','8764950896','US1'

 select * from inventorysuppliers
 -- * from Supplier



 --testing pgntion
 SELECT c.* FROM (
	SELECT ROW_NUMBER() OVER(order  BY  uid ) AS RowID,*  FROM inventorysuppliers
    ) AS c  WHERE c.RowID > '0' AND c.RowID <= '3 '

    SELECT * FROM inventorysuppliers where uid='US1' and ProdName='sweet pepper'*/


	/*
create proc norder @total money,@item varchar(30), @pr smallmoney,@qty smallint,@sub money,@uid varchar(32)
as
begin
		insert into Orders(TotalPrice,UID) values('2300','US1')
		declare @or varchar(32),
		@or = select ORID ,UID,OrderDate from Orders where /*UID='US1' and OrderDate=(select MAX(cre) from Orders where UID='US1') 
		 
		 SELECT * FROM Orders




		 delete Orders






 alter FUNCTION dbo.salesdata(@id varchar(32))
RETURNS @itable table (prodname varchar(32) , amtsold int) 
As
Begin
Declare @prod varchar (30),
  @s int, 
  @c int, 
  @counter int
  Select @c= count(*) from Inventory where uid=@id
  --print @c
  set @counter=1
  While(@counter<=@c) 
  Begin
		SELECT  @prod= ProdName
From 
(
    Select 
      Row_Number() Over (Order By INID) As RowNum
    , *
    From Inventory where uid=@id
)t2
Where RowNum = @counter and  uid=@id
	--print @prod
		select @s= sum(Qty) 	from OrderLine where prodname=@prod AND uid=@id
		
		--print @s
		insert into @itable values (@prod, @s)
		set @counter = @counter+1
	end
Return
END
select * FROM  dbo.salesdata ('US1')
SELECT TOP (1) ProdName from  Inventory




-- function to displqay sales data
alter Function dtsl(@id varchar(32))
returns @dtable table (tday int,tweek int,tmonth int,tyear int  )
as 
begin
Declare @dt  int,
@wk int,
@m int,
@y int
	select @dt=sum(TotalPrice) from Orders where day (created_at) = day(CURRENT_TIMESTAMP ) and UID=@id
	select @m= sum(TotalPrice) from Orders where month (created_at) = month(CURRENT_TIMESTAMP ) and UID=@id
	select @y= sum(TotalPrice) from Orders where year(created_at) = year (CURRENT_TIMESTAMP ) and UID=@id
	select *@wk=* sum(TotalPrice)  from Orders where datepart (WEEK,created_at) = datepart (WEEK, CURRENT_TIMESTAMP ) and UID=@id 'US1'
    insert into  @dtable values (@dt,@wk,@m,@y)
  select * from Orders
	RETURN
END
select * from dtsl('US1')
SELECT * FROM  Inventory WHERE uid ='US1'*/

select * from users









