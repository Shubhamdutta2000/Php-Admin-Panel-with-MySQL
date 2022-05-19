# Php-Admin-Panel
> Admin panel where admin can able to add, view and delete tender and eoi with pdf upload feature with proper authentication with mysql database.

<br />

## How to Use

- Clone this project in the htdocs folder in xampp folder:
  ```
    git clone https://github.com/Shubhamdutta2000/Php-Admin-Panel.git

  ```
- Start Xampp apache server and mysql server
- Then create Db and add 3 tables 
  ```
    DB: grseDB
    Tables: tendertable, eoitable, cred
  ```
- Then open ```http://localhost/Php-Admin-Panel```


<br />

## Tables:

- ***tendertable***:

| id | unitName | eoiDetails | eoiNum | dateOfEoiPub | closingDateTime | pdfName | bidderPreQualification | biddingInstruction |
| -- | -------- | ---------- | ------ | ------------ | --------------- | ------- | ---------------------- | ------------------ |
| 1 | GRSE UNIT |	Notice inviting Expression of Interest (EOI) | EOI/COSEC/01/2022 | 18-Apr-2022 | 10-May-2022 | sample.pdf | NA | NA |

<br />

- ***eoitable***

| id | unitName | enquiryNum | enquiryDate | tenderDetails | status | closingDateTime | costOfTenderDoc | emd | pdfName | bidderPreQualification | biddingInstruction |
| -- | -------- | ---------- | ----------- | ------------- | ------ | --------------- | --------------- | --- |-------- | ---------------------- | ------------------ |
| 1 | GRSE UNIT |	SCC/AJK/ST/LAUNCH HI RING/015/ET-1856 | 18-Apr-2022 |	Hiring of Launch service | single | 10-May-2022:11:00:00pm | 120.8 | 787 | sample.pdf | NA | NA |

<br />

- ***cred***

| userId |
| ------ |
| admin |

<br />
<br />

## License
- MIT
