<?php

/**
 * Aptitude question bank — consumed by AptitudeCatalogSeeder.
 *
 * Structure: [ test_slug => [ question, ... ] ]
 * Each question: [
 *   'q' => body text,
 *   'd' => difficulty (beginner|intermediate|advanced),
 *   'e' => explanation shown after submission,
 *   'o' => [ [option text, is_correct(1|0)], ... ]   // exactly one correct
 * ]
 *
 * NOTE: This is a starter bank sized so every test is runnable end-to-end.
 * It is genuine, self-checked content but should be reviewed and expanded
 * by a subject-matter editor before heavy production use.
 */

return [

/* ─────────────────────────── SKILL: NUMERICAL ─────────────────────────── */
'numerical' => [
    ['q' => 'A shirt costs ₦4,000. During a sale it is discounted by 25%. What is the sale price?', 'd' => 'beginner', 'e' => '25% of 4,000 is 1,000, so 4,000 − 1,000 = 3,000.', 'o' => [['₦3,000', 1], ['₦3,250', 0], ['₦3,500', 0], ['₦2,750', 0]]],
    ['q' => 'If 5 workers build a wall in 12 days, how many days would 10 workers take (same rate)?', 'd' => 'beginner', 'e' => 'Twice the workers means half the time: 12 ÷ 2 = 6 days.', 'o' => [['6 days', 1], ['24 days', 0], ['10 days', 0], ['8 days', 0]]],
    ['q' => 'What is 15% of 200?', 'd' => 'beginner', 'e' => '15% = 0.15; 0.15 × 200 = 30.', 'o' => [['30', 1], ['15', 0], ['35', 0], ['300', 0]]],
    ['q' => 'A car travels 240 km in 3 hours. What is its average speed?', 'd' => 'beginner', 'e' => 'Speed = distance ÷ time = 240 ÷ 3 = 80 km/h.', 'o' => [['80 km/h', 1], ['720 km/h', 0], ['60 km/h', 0], ['120 km/h', 0]]],
    ['q' => 'The ratio of boys to girls in a class is 3:2. If there are 30 students, how many are girls?', 'd' => 'intermediate', 'e' => 'Total parts = 5; each part = 30 ÷ 5 = 6; girls = 2 × 6 = 12.', 'o' => [['12', 1], ['18', 0], ['10', 0], ['15', 0]]],
    ['q' => 'A sum of ₦50,000 earns simple interest at 8% per year. What is the interest after 2 years?', 'd' => 'intermediate', 'e' => 'SI = P×R×T/100 = 50,000×8×2/100 = 8,000.', 'o' => [['₦8,000', 1], ['₦4,000', 0], ['₦16,000', 0], ['₦10,000', 0]]],
    ['q' => 'If a price rises from ₦80 to ₦100, what is the percentage increase?', 'd' => 'intermediate', 'e' => 'Increase = 20; 20 ÷ 80 × 100 = 25%.', 'o' => [['25%', 1], ['20%', 0], ['30%', 0], ['12.5%', 0]]],
    ['q' => 'A table shows sales of 120, 150, 180 and 150 units over four months. What is the mean monthly sales?', 'd' => 'intermediate', 'e' => 'Sum = 600; mean = 600 ÷ 4 = 150.', 'o' => [['150', 1], ['160', 0], ['145', 0], ['180', 0]]],
    ['q' => 'A shop marks up cost by 40% then gives a 10% discount on the marked price. On a ₦1,000 cost item, what is the final selling price?', 'd' => 'advanced', 'e' => 'Marked = 1,400; discount 10% = 140; final = 1,260.', 'o' => [['₦1,260', 1], ['₦1,300', 0], ['₦1,240', 0], ['₦1,400', 0]]],
    ['q' => 'Two taps fill a tank in 6 and 12 hours respectively. Working together, how long to fill it?', 'd' => 'advanced', 'e' => 'Rates: 1/6 + 1/12 = 3/12 = 1/4 per hour, so 4 hours.', 'o' => [['4 hours', 1], ['9 hours', 0], ['3 hours', 0], ['6 hours', 0]]],
],

/* ─────────────────────────── SKILL: VERBAL ─────────────────────────── */
'verbal' => [
    ['q' => 'Choose the word most similar in meaning to "abundant".', 'd' => 'beginner', 'e' => '"Plentiful" is the closest synonym to abundant.', 'o' => [['Plentiful', 1], ['Scarce', 0], ['Empty', 0], ['Rare', 0]]],
    ['q' => 'Choose the word most opposite in meaning to "expand".', 'd' => 'beginner', 'e' => 'To "contract" is the antonym of expand.', 'o' => [['Contract', 1], ['Enlarge', 0], ['Stretch', 0], ['Grow', 0]]],
    ['q' => 'Complete the sentence: "The manager asked the team to ___ the report before Friday."', 'd' => 'beginner', 'e' => '"Submit" fits the context of handing in a report.', 'o' => [['submit', 1], ['submits', 0], ['submitting', 0], ['submitted', 0]]],
    ['q' => '"All the staff were praised for their punctuality." What does "punctuality" mean?', 'd' => 'beginner', 'e' => 'Punctuality means being on time.', 'o' => [['Being on time', 1], ['Being polite', 0], ['Working hard', 0], ['Being honest', 0]]],
    ['q' => 'Passage: "Solar power is renewable, but installation costs remain high." Statement: "Solar power costs nothing." True, False, or Cannot Say?', 'd' => 'intermediate', 'e' => 'The passage says installation costs are high, so the statement is False.', 'o' => [['False', 1], ['True', 0], ['Cannot Say', 0], ['Partly true', 0]]],
    ['q' => 'Passage: "The library opens at 9am on weekdays." Statement: "The library opens at 9am on Saturday." True, False, or Cannot Say?', 'd' => 'intermediate', 'e' => 'Saturday is not a weekday and is not mentioned, so we Cannot Say.', 'o' => [['Cannot Say', 1], ['True', 0], ['False', 0], ['Never', 0]]],
    ['q' => 'Which word correctly completes: "Neither the manager nor the staff ___ available."', 'd' => 'intermediate', 'e' => 'With "neither/nor", the verb agrees with the nearer subject "staff" → "were".', 'o' => [['were', 1], ['was', 0], ['is', 0], ['being', 0]]],
    ['q' => 'Select the correctly spelled word.', 'd' => 'intermediate', 'e' => '"Accommodate" has double c and double m.', 'o' => [['Accommodate', 1], ['Acommodate', 0], ['Accomodate', 0], ['Acomodate', 0]]],
    ['q' => 'Passage: "Every employee who completed training received a certificate. Musa did not receive a certificate." Which conclusion follows?', 'd' => 'advanced', 'e' => 'If all who completed training got a certificate and Musa did not, Musa did not complete training.', 'o' => [['Musa did not complete the training', 1], ['Musa failed the training', 0], ['Musa was absent from work', 0], ['Musa completed the training', 0]]],
    ['q' => 'Choose the word that best completes the analogy: Author is to Book as Composer is to ___.', 'd' => 'advanced', 'e' => 'An author creates a book; a composer creates a symphony/music.', 'o' => [['Symphony', 1], ['Orchestra', 0], ['Instrument', 0], ['Concert', 0]]],
],

/* ─────────────────────────── SKILL: LOGICAL ─────────────────────────── */
'logical' => [
    ['q' => 'What comes next in the sequence: 2, 4, 8, 16, ___?', 'd' => 'beginner', 'e' => 'Each term doubles: 16 × 2 = 32.', 'o' => [['32', 1], ['24', 0], ['20', 0], ['64', 0]]],
    ['q' => 'What comes next: 3, 6, 9, 12, ___?', 'd' => 'beginner', 'e' => 'The sequence increases by 3: 12 + 3 = 15.', 'o' => [['15', 1], ['14', 0], ['16', 0], ['18', 0]]],
    ['q' => 'If all cats are animals, and some animals are pets, which statement must be true?', 'd' => 'beginner', 'e' => 'Only "all cats are animals" is guaranteed by the premises.', 'o' => [['All cats are animals', 1], ['All animals are cats', 0], ['All pets are cats', 0], ['No cats are pets', 0]]],
    ['q' => 'Find the odd one out: Circle, Square, Triangle, Cube.', 'd' => 'beginner', 'e' => 'A cube is 3-D; the others are 2-D shapes.', 'o' => [['Cube', 1], ['Circle', 0], ['Square', 0], ['Triangle', 0]]],
    ['q' => 'What comes next: 1, 4, 9, 16, ___?', 'd' => 'intermediate', 'e' => 'These are perfect squares: 5² = 25.', 'o' => [['25', 1], ['20', 0], ['24', 0], ['32', 0]]],
    ['q' => 'If MONDAY is coded as NPOEBZ, how is FRIDAY coded (each letter +1)?', 'd' => 'intermediate', 'e' => 'Shift each letter forward by one: F→G, R→S, I→J, D→E, A→B, Y→Z = GSJEBZ.', 'o' => [['GSJEBZ', 1], ['GSHEBZ', 0], ['ESJEBZ', 0], ['GSJECZ', 0]]],
    ['q' => 'A is taller than B. C is taller than A. Who is the tallest?', 'd' => 'intermediate', 'e' => 'C > A > B, so C is tallest.', 'o' => [['C', 1], ['A', 0], ['B', 0], ['Cannot tell', 0]]],
    ['q' => 'What comes next: 2, 3, 5, 8, 13, ___?', 'd' => 'intermediate', 'e' => 'Fibonacci-style: each term is the sum of the previous two: 8 + 13 = 21.', 'o' => [['21', 1], ['18', 0], ['20', 0], ['24', 0]]],
    ['q' => 'All managers attended the meeting. Some who attended were late. Which conclusion is valid?', 'd' => 'advanced', 'e' => 'We only know all managers attended; whether any manager was late is not stated, so "some attendees were late" is all we can affirm.', 'o' => [['Some attendees were late', 1], ['All managers were late', 0], ['No manager was late', 0], ['All late people were managers', 0]]],
    ['q' => 'In a race, Ada finished before Bola but after Chika. Dele finished before Chika. Who finished first?', 'd' => 'advanced', 'e' => 'Order: Dele < Chika < Ada < Bola, so Dele finished first.', 'o' => [['Dele', 1], ['Chika', 0], ['Ada', 0], ['Bola', 0]]],
],

/* ─────────────────────────── SKILL: ABSTRACT ─────────────────────────── */
'abstract' => [
    ['q' => 'A pattern rotates a single arrow 90° clockwise each step. If it starts pointing up, where does it point after two steps?', 'd' => 'beginner', 'e' => 'Up → right (90°) → down (180°).', 'o' => [['Down', 1], ['Left', 0], ['Right', 0], ['Up', 0]]],
    ['q' => 'In a series the number of dots increases by one each figure: 1, 2, 3, ___. How many dots in the next figure?', 'd' => 'beginner', 'e' => 'The count increases by one: after 3 comes 4.', 'o' => [['4', 1], ['5', 0], ['3', 0], ['6', 0]]],
    ['q' => 'A shape alternates black, white, black, white. If the 1st is black, what colour is the 6th?', 'd' => 'beginner', 'e' => 'Odd positions are black, even are white; the 6th is white.', 'o' => [['White', 1], ['Black', 0], ['Grey', 0], ['Cannot tell', 0]]],
    ['q' => 'A square gains one extra side each step to become a pentagon, hexagon... How many sides after the hexagon?', 'd' => 'beginner', 'e' => 'Hexagon (6) + 1 = heptagon with 7 sides.', 'o' => [['7', 1], ['8', 0], ['6', 0], ['5', 0]]],
    ['q' => 'A figure is reflected across a vertical mirror. A shape on the left appears where?', 'd' => 'intermediate', 'e' => 'A vertical mirror swaps left and right, so it appears on the right.', 'o' => [['On the right', 1], ['On the left', 0], ['At the top', 0], ['Unchanged', 0]]],
    ['q' => 'Sequence of rotations: 0°, 45°, 90°, 135°. What is the next rotation?', 'd' => 'intermediate', 'e' => 'Each step adds 45°: 135 + 45 = 180°.', 'o' => [['180°', 1], ['160°', 0], ['200°', 0], ['150°', 0]]],
    ['q' => 'In a grid, each row adds one shaded cell from left to right. Row 1 has 1 shaded, row 2 has 2. How many does row 4 have?', 'd' => 'intermediate', 'e' => 'Row n has n shaded cells, so row 4 has 4.', 'o' => [['4', 1], ['3', 0], ['5', 0], ['8', 0]]],
    ['q' => 'A pattern doubles the number of small triangles each step: 1, 2, 4, ___. Next?', 'd' => 'intermediate', 'e' => 'Doubling: 4 × 2 = 8.', 'o' => [['8', 1], ['6', 0], ['7', 0], ['16', 0]]],
    ['q' => 'A large arrow turns 120° anticlockwise each step. After three steps, how far has it turned in total?', 'd' => 'advanced', 'e' => '3 × 120° = 360°, a full turn back to the start.', 'o' => [['360°', 1], ['240°', 0], ['120°', 0], ['300°', 0]]],
    ['q' => 'Figures follow the rule: number of sides × 2 = number of dots inside. A pentagon (5 sides) contains how many dots?', 'd' => 'advanced', 'e' => '5 sides × 2 = 10 dots.', 'o' => [['10', 1], ['7', 0], ['5', 0], ['12', 0]]],
],

/* ─────────────────────────── SKILL: ICT ─────────────────────────── */
'ict' => [
    ['q' => 'Which of these is a web browser?', 'd' => 'beginner', 'e' => 'Google Chrome is a web browser; the others are not.', 'o' => [['Google Chrome', 1], ['Microsoft Excel', 0], ['Windows', 0], ['Photoshop', 0]]],
    ['q' => 'What does "CPU" stand for?', 'd' => 'beginner', 'e' => 'CPU = Central Processing Unit.', 'o' => [['Central Processing Unit', 1], ['Computer Personal Unit', 0], ['Central Program Utility', 0], ['Control Processing Unit', 0]]],
    ['q' => 'In a spreadsheet, which symbol begins a formula?', 'd' => 'beginner', 'e' => 'Formulas in Excel/Sheets start with "=".', 'o' => [['=', 1], ['+', 0], ['#', 0], ['@', 0]]],
    ['q' => 'Which key combination is commonly used to copy selected text?', 'd' => 'beginner', 'e' => 'Ctrl + C copies; Ctrl + V pastes.', 'o' => [['Ctrl + C', 1], ['Ctrl + P', 0], ['Ctrl + X', 0], ['Ctrl + Z', 0]]],
    ['q' => 'Which file type is an image?', 'd' => 'intermediate', 'e' => '.png is an image format; .docx, .xlsx, .mp3 are not images.', 'o' => [['.png', 1], ['.docx', 0], ['.xlsx', 0], ['.mp3', 0]]],
    ['q' => 'What does the "cloud" refer to in cloud storage?', 'd' => 'intermediate', 'e' => 'Cloud storage keeps files on remote internet servers.', 'o' => [['Remote servers accessed over the internet', 1], ['Your computer hard drive', 0], ['A USB flash drive', 0], ['The computer\'s RAM', 0]]],
    ['q' => 'In Excel, which function adds a range of cells?', 'd' => 'intermediate', 'e' => 'SUM adds the values in a range, e.g. =SUM(A1:A10).', 'o' => [['SUM', 1], ['COUNT', 0], ['IF', 0], ['MAX', 0]]],
    ['q' => 'Which of these is a strong password practice?', 'd' => 'intermediate', 'e' => 'Mixing letters, numbers and symbols makes a stronger password.', 'o' => [['Use a mix of letters, numbers and symbols', 1], ['Use your birthday', 0], ['Use "password123"', 0], ['Reuse one password everywhere', 0]]],
    ['q' => 'What does "phishing" describe?', 'd' => 'advanced', 'e' => 'Phishing tricks people into revealing sensitive data via fake messages.', 'o' => [['A scam to steal personal information via fake messages', 1], ['A way to speed up the internet', 0], ['A type of computer hardware', 0], ['A file backup method', 0]]],
    ['q' => 'In a spreadsheet, what will =IF(A1>50,"Pass","Fail") return if A1 is 40?', 'd' => 'advanced', 'e' => '40 is not greater than 50, so the formula returns "Fail".', 'o' => [['Fail', 1], ['Pass', 0], ['40', 0], ['Error', 0]]],
],

/* ─────────────────────────── SKILL: GENERAL APTITUDE ─────────────────────────── */
'general-aptitude' => [
    ['q' => 'What is 12 × 8?', 'd' => 'beginner', 'e' => '12 × 8 = 96.', 'o' => [['96', 1], ['86', 0], ['104', 0], ['92', 0]]],
    ['q' => 'Choose the synonym of "rapid".', 'd' => 'beginner', 'e' => '"Quick" is a synonym of rapid.', 'o' => [['Quick', 1], ['Slow', 0], ['Heavy', 0], ['Quiet', 0]]],
    ['q' => 'Next in sequence: 5, 10, 15, 20, ___?', 'd' => 'beginner', 'e' => 'Increases by 5: 20 + 5 = 25.', 'o' => [['25', 1], ['24', 0], ['30', 0], ['22', 0]]],
    ['q' => 'If today is Wednesday, what day is it in 3 days?', 'd' => 'beginner', 'e' => 'Wed + 3 = Saturday.', 'o' => [['Saturday', 1], ['Friday', 0], ['Sunday', 0], ['Monday', 0]]],
    ['q' => 'A product costs ₦2,500 and is sold for ₦3,000. What is the profit?', 'd' => 'intermediate', 'e' => 'Profit = 3,000 − 2,500 = 500.', 'o' => [['₦500', 1], ['₦2,500', 0], ['₦300', 0], ['₦550', 0]]],
    ['q' => 'Which number is a prime?', 'd' => 'intermediate', 'e' => '13 has no divisors other than 1 and itself; 9, 15, 21 are composite.', 'o' => [['13', 1], ['9', 0], ['15', 0], ['21', 0]]],
    ['q' => 'Complete the analogy: Hand is to Glove as Foot is to ___.', 'd' => 'intermediate', 'e' => 'A glove covers a hand; a sock covers a foot.', 'o' => [['Sock', 1], ['Shoe rack', 0], ['Toe', 0], ['Leg', 0]]],
    ['q' => 'If 3 pens cost ₦450, what is the cost of 5 pens?', 'd' => 'intermediate', 'e' => 'One pen = 150; five pens = 750.', 'o' => [['₦750', 1], ['₦650', 0], ['₦900', 0], ['₦700', 0]]],
    ['q' => 'A clock shows 3:15. What is the angle between the hour and minute hands (approx)?', 'd' => 'advanced', 'e' => 'At 3:15 the minute hand is at 90° and the hour hand at ~97.5°, giving about 7.5°.', 'o' => [['About 7.5°', 1], ['0°', 0], ['90°', 0], ['30°', 0]]],
    ['q' => 'The average of five numbers is 20. If four of them sum to 70, what is the fifth?', 'd' => 'advanced', 'e' => 'Total = 5 × 20 = 100; fifth = 100 − 70 = 30.', 'o' => [['30', 1], ['20', 0], ['25', 0], ['35', 0]]],
],

/* ─────────────────────────── ROLE: SOFTWARE DEVELOPER ─────────────────────────── */
'software-developer' => [
    ['q' => 'What does a "variable" store in a program?', 'd' => 'beginner', 'e' => 'A variable holds a value that can be referenced and changed.', 'o' => [['A value that can change', 1], ['A permanent constant only', 0], ['A network address', 0], ['A printer setting', 0]]],
    ['q' => 'Which data structure works on a Last-In-First-Out (LIFO) basis?', 'd' => 'intermediate', 'e' => 'A stack is LIFO; a queue is FIFO.', 'o' => [['Stack', 1], ['Queue', 0], ['Array', 0], ['Tree', 0]]],
    ['q' => 'What is the output type of a boolean expression?', 'd' => 'beginner', 'e' => 'A boolean evaluates to true or false.', 'o' => [['true or false', 1], ['a number 0–9', 0], ['any string', 0], ['an array', 0]]],
    ['q' => 'In Big-O notation, which is fastest for large n?', 'd' => 'advanced', 'e' => 'O(log n) grows slower than O(n), O(n log n) and O(n²).', 'o' => [['O(log n)', 1], ['O(n)', 0], ['O(n log n)', 0], ['O(n²)', 0]]],
    ['q' => 'What does a "for" loop do?', 'd' => 'beginner', 'e' => 'A for loop repeats a block a set number of times.', 'o' => [['Repeats code a number of times', 1], ['Defines a function', 0], ['Declares a class', 0], ['Ends the program', 0]]],
    ['q' => 'Which keyword typically defines a reusable block of code?', 'd' => 'intermediate', 'e' => 'A function (or method) groups reusable code.', 'o' => [['function', 1], ['loop', 0], ['import', 0], ['return only', 0]]],
    ['q' => 'What is the result of the integer expression 7 % 3 (modulo)?', 'd' => 'intermediate', 'e' => 'Modulo returns the remainder: 7 ÷ 3 leaves remainder 1.', 'o' => [['1', 1], ['2', 0], ['3', 0], ['0', 0]]],
    ['q' => 'In version control (Git), what does "commit" do?', 'd' => 'intermediate', 'e' => 'A commit records a snapshot of staged changes to the repository history.', 'o' => [['Saves a snapshot of changes to history', 1], ['Deletes the repository', 0], ['Uploads to production automatically', 0], ['Formats the code', 0]]],
    ['q' => 'Which of these best describes an array?', 'd' => 'beginner', 'e' => 'An array is an ordered collection of elements accessed by index.', 'o' => [['An ordered collection accessed by index', 1], ['A single true/false value', 0], ['A function with no return', 0], ['A comment in code', 0]]],
    ['q' => 'A function should sort a list ascending but returns it descending. What is the most likely cause?', 'd' => 'advanced', 'e' => 'A reversed comparison (e.g. b−a instead of a−b) sorts in the wrong direction.', 'o' => [['The comparison operator is reversed', 1], ['The list is too long', 0], ['The variable names are wrong', 0], ['The file was not saved', 0]]],
],

/* ─────────────────────────── ROLE: DATA ANALYSIS & SQL ─────────────────────────── */
'data-analysis' => [
    ['q' => 'Which SQL clause filters rows returned by a query?', 'd' => 'beginner', 'e' => 'WHERE filters rows before grouping/returning.', 'o' => [['WHERE', 1], ['ORDER BY', 0], ['SELECT', 0], ['JOIN', 0]]],
    ['q' => 'Which SQL keyword removes duplicate rows from results?', 'd' => 'intermediate', 'e' => 'SELECT DISTINCT returns unique rows.', 'o' => [['DISTINCT', 1], ['UNIQUE', 0], ['GROUP', 0], ['LIMIT', 0]]],
    ['q' => 'What does the SQL function COUNT(*) return?', 'd' => 'beginner', 'e' => 'COUNT(*) returns the number of rows.', 'o' => [['The number of rows', 1], ['The sum of a column', 0], ['The largest value', 0], ['The column names', 0]]],
    ['q' => 'In a dataset, the "median" is best described as:', 'd' => 'intermediate', 'e' => 'The median is the middle value when data is sorted.', 'o' => [['The middle value when sorted', 1], ['The most frequent value', 0], ['The average of all values', 0], ['The largest value', 0]]],
    ['q' => 'Which SQL clause groups rows to apply aggregate functions?', 'd' => 'intermediate', 'e' => 'GROUP BY groups rows so aggregates like SUM apply per group.', 'o' => [['GROUP BY', 1], ['WHERE', 0], ['HAVING only', 0], ['ORDER BY', 0]]],
    ['q' => 'A column has values 2, 4, 4, 6, 9. What is the mode?', 'd' => 'beginner', 'e' => 'The mode is the most frequent value: 4 appears twice.', 'o' => [['4', 1], ['5', 0], ['6', 0], ['9', 0]]],
    ['q' => 'Which chart type best shows change in a value over time?', 'd' => 'beginner', 'e' => 'A line chart is standard for trends over time.', 'o' => [['Line chart', 1], ['Pie chart', 0], ['Table', 0], ['Scatter of one point', 0]]],
    ['q' => 'What does a JOIN do in SQL?', 'd' => 'intermediate', 'e' => 'A JOIN combines rows from two or more tables based on a related column.', 'o' => [['Combines rows from related tables', 1], ['Deletes a table', 0], ['Sorts one column', 0], ['Renames a database', 0]]],
    ['q' => 'To find the average order value per customer, which combination is correct?', 'd' => 'advanced', 'e' => 'AVG(amount) with GROUP BY customer_id gives the average per customer.', 'o' => [['AVG(amount) ... GROUP BY customer_id', 1], ['SUM(amount) only', 0], ['COUNT(*) ... ORDER BY amount', 0], ['MAX(amount) ... WHERE customer_id', 0]]],
    ['q' => 'A report shows total sales doubled but units sold stayed flat. What most likely happened?', 'd' => 'advanced', 'e' => 'If units are flat but revenue doubled, average price rose.', 'o' => [['Average price increased', 1], ['More customers churned', 0], ['Costs decreased', 0], ['The data has no explanation', 0]]],
],

/* ─────────────────────────── ROLE: ACCOUNTING FUNDAMENTALS ─────────────────────────── */
'accounting-fundamentals' => [
    ['q' => 'The accounting equation is: Assets = Liabilities + ___.', 'd' => 'beginner', 'e' => 'Assets = Liabilities + Equity (Owner\'s Capital).', 'o' => [['Equity', 1], ['Revenue', 0], ['Expenses', 0], ['Cash', 0]]],
    ['q' => 'Which financial statement shows profit or loss over a period?', 'd' => 'beginner', 'e' => 'The income statement (P&L) shows profit or loss for a period.', 'o' => [['Income statement', 1], ['Balance sheet', 0], ['Cash flow only', 0], ['Trial balance', 0]]],
    ['q' => 'In double-entry bookkeeping, each transaction affects at least how many accounts?', 'd' => 'beginner', 'e' => 'Double-entry means every transaction has a debit and a credit — at least two accounts.', 'o' => [['Two', 1], ['One', 0], ['Three', 0], ['Zero', 0]]],
    ['q' => 'An increase in an asset account is recorded as a:', 'd' => 'intermediate', 'e' => 'Assets increase on the debit side.', 'o' => [['Debit', 1], ['Credit', 0], ['Neither', 0], ['Both equally', 0]]],
    ['q' => 'What does "accounts receivable" represent?', 'd' => 'intermediate', 'e' => 'Accounts receivable is money owed to the business by customers.', 'o' => [['Money owed to the business by customers', 1], ['Money the business owes suppliers', 0], ['Cash in the bank', 0], ['Owner\'s salary', 0]]],
    ['q' => 'Depreciation is best described as:', 'd' => 'intermediate', 'e' => 'Depreciation spreads the cost of a fixed asset over its useful life.', 'o' => [['Spreading an asset\'s cost over its useful life', 1], ['An increase in asset value', 0], ['A type of revenue', 0], ['Cash paid to owners', 0]]],
    ['q' => 'Revenue of ₦900,000 and expenses of ₦650,000 give a net profit of:', 'd' => 'beginner', 'e' => '900,000 − 650,000 = 250,000.', 'o' => [['₦250,000', 1], ['₦150,000', 0], ['₦1,550,000', 0], ['₦350,000', 0]]],
    ['q' => 'Which item appears on the balance sheet, not the income statement?', 'd' => 'intermediate', 'e' => 'Equipment is an asset on the balance sheet; the others are income-statement items.', 'o' => [['Equipment', 1], ['Sales revenue', 0], ['Rent expense', 0], ['Salaries expense', 0]]],
    ['q' => 'A trial balance does not balance. Which is a possible cause?', 'd' => 'advanced', 'e' => 'Posting only one side of an entry (e.g. debit without the matching credit) unbalances the trial balance.', 'o' => [['Only one side of an entry was posted', 1], ['Both sides were posted correctly', 0], ['The company made a profit', 0], ['Cash increased', 0]]],
    ['q' => 'Gross profit is calculated as:', 'd' => 'advanced', 'e' => 'Gross profit = Sales revenue − Cost of goods sold.', 'o' => [['Sales revenue − Cost of goods sold', 1], ['Sales revenue − All expenses', 0], ['Assets − Liabilities', 0], ['Revenue + Expenses', 0]]],
],

/* ─────────────────────────── ROLE: FINANCIAL REASONING ─────────────────────────── */
'financial-reasoning' => [
    ['q' => 'If revenue is ₦2,000,000 and net profit is ₦300,000, what is the net profit margin?', 'd' => 'intermediate', 'e' => 'Margin = 300,000 ÷ 2,000,000 × 100 = 15%.', 'o' => [['15%', 1], ['30%', 0], ['6.7%', 0], ['20%', 0]]],
    ['q' => 'A company\'s current assets are ₦500,000 and current liabilities ₦250,000. What is the current ratio?', 'd' => 'intermediate', 'e' => 'Current ratio = 500,000 ÷ 250,000 = 2.', 'o' => [['2.0', 1], ['0.5', 0], ['1.5', 0], ['2.5', 0]]],
    ['q' => 'An investment of ₦100,000 grows to ₦121,000 in 2 years. What is the total percentage return?', 'd' => 'intermediate', 'e' => 'Gain = 21,000; 21,000 ÷ 100,000 × 100 = 21%.', 'o' => [['21%', 1], ['10%', 0], ['12.1%', 0], ['20%', 0]]],
    ['q' => 'Which ratio measures a company\'s ability to pay short-term obligations?', 'd' => 'beginner', 'e' => 'The current (liquidity) ratio measures short-term solvency.', 'o' => [['Current ratio', 1], ['Debt-to-equity', 0], ['Gross margin', 0], ['Return on assets', 0]]],
    ['q' => 'If fixed costs are ₦400,000 and the contribution per unit is ₦2,000, what is the break-even volume?', 'd' => 'advanced', 'e' => 'Break-even = fixed costs ÷ contribution = 400,000 ÷ 2,000 = 200 units.', 'o' => [['200 units', 1], ['800 units', 0], ['20 units', 0], ['2,000 units', 0]]],
    ['q' => 'A higher debt-to-equity ratio generally indicates:', 'd' => 'intermediate', 'e' => 'More debt relative to equity means higher financial leverage and risk.', 'o' => [['Higher financial leverage and risk', 1], ['Lower risk', 0], ['More cash on hand', 0], ['Higher profit guaranteed', 0]]],
    ['q' => 'Company A: margin 10%, revenue ₦5m. Company B: margin 20%, revenue ₦2m. Which earns more profit?', 'd' => 'advanced', 'e' => 'A: 0.10 × 5m = 500k; B: 0.20 × 2m = 400k. A earns more.', 'o' => [['Company A (₦500,000)', 1], ['Company B (₦400,000)', 0], ['They are equal', 0], ['Cannot be determined', 0]]],
    ['q' => 'Compound interest differs from simple interest because it:', 'd' => 'intermediate', 'e' => 'Compound interest earns interest on previously accumulated interest.', 'o' => [['Earns interest on accumulated interest', 1], ['Is always lower', 0], ['Ignores the principal', 0], ['Is only for loans', 0]]],
    ['q' => 'Sales rose 25% then fell 20% the next year. Versus the start, sales are now:', 'd' => 'advanced', 'e' => '1.25 × 0.80 = 1.00 — back to the original level.', 'o' => [['Unchanged', 1], ['Up 5%', 0], ['Down 5%', 0], ['Up 45%', 0]]],
    ['q' => 'Which is a "fixed cost" for a bakery?', 'd' => 'beginner', 'e' => 'Monthly shop rent stays the same regardless of output — a fixed cost.', 'o' => [['Monthly shop rent', 1], ['Flour used per loaf', 0], ['Packaging per item', 0], ['Hourly wages of casual staff', 0]]],
],

/* ─────────────────────────── ROLE: DIGITAL MARKETING ─────────────────────────── */
'digital-marketing' => [
    ['q' => 'What does "SEO" stand for?', 'd' => 'beginner', 'e' => 'SEO = Search Engine Optimisation.', 'o' => [['Search Engine Optimisation', 1], ['Social Engagement Online', 0], ['Sales Enablement Operations', 0], ['Search Email Outreach', 0]]],
    ['q' => 'Click-Through Rate (CTR) is calculated as:', 'd' => 'intermediate', 'e' => 'CTR = clicks ÷ impressions × 100.', 'o' => [['Clicks ÷ Impressions × 100', 1], ['Impressions ÷ Clicks', 0], ['Clicks ÷ Conversions', 0], ['Spend ÷ Clicks', 0]]],
    ['q' => 'Which metric measures the cost to acquire one customer?', 'd' => 'intermediate', 'e' => 'CAC (Customer Acquisition Cost) is spend ÷ new customers.', 'o' => [['CAC', 1], ['CTR', 0], ['CPM', 0], ['Bounce rate', 0]]],
    ['q' => 'A "conversion" in a campaign usually means:', 'd' => 'beginner', 'e' => 'A conversion is a desired action (purchase, sign-up, etc.).', 'o' => [['A user completes a desired action', 1], ['A user sees an ad', 0], ['A page loads', 0], ['An email bounces', 0]]],
    ['q' => 'If ₦50,000 ad spend brings 100 sales, the cost per acquisition is:', 'd' => 'intermediate', 'e' => 'CPA = 50,000 ÷ 100 = 500.', 'o' => [['₦500', 1], ['₦5,000', 0], ['₦50', 0], ['₦1,000', 0]]],
    ['q' => '"Organic" traffic refers to visitors who arrive:', 'd' => 'beginner', 'e' => 'Organic traffic comes from unpaid search results.', 'o' => [['From unpaid search results', 1], ['By clicking paid ads', 0], ['Through purchased email lists', 0], ['From printed flyers', 0]]],
    ['q' => 'A landing page has high traffic but very few conversions. The best first step is to:', 'd' => 'advanced', 'e' => 'Improve the page/offer and call-to-action to lift conversion rate, since traffic is already high.', 'o' => [['Improve the page offer and call-to-action', 1], ['Buy more traffic immediately', 0], ['Delete the page', 0], ['Ignore it', 0]]],
    ['q' => 'CPM stands for cost per:', 'd' => 'intermediate', 'e' => 'CPM = cost per mille — cost per 1,000 impressions.', 'o' => [['1,000 impressions', 1], ['click', 0], ['conversion', 0], ['customer', 0]]],
    ['q' => 'Which is the best KPI for a brand-awareness campaign?', 'd' => 'advanced', 'e' => 'Reach/impressions measure how many people saw the brand — the goal of awareness.', 'o' => [['Reach / impressions', 1], ['Cost per acquisition', 0], ['Return on ad spend', 0], ['Cart abandonment rate', 0]]],
    ['q' => 'A/B testing is used to:', 'd' => 'intermediate', 'e' => 'A/B testing compares two variants to see which performs better.', 'o' => [['Compare two versions to see which performs better', 1], ['Double the ad budget', 0], ['Block bot traffic', 0], ['Translate a page', 0]]],
],

/* ─────────────────────────── ROLE: OFFICE & ADMIN ─────────────────────────── */
'office-admin' => [
    ['q' => 'Which is the most professional way to open a formal business email?', 'd' => 'beginner', 'e' => '"Dear Mr Adeyemi," is an appropriate formal salutation.', 'o' => [['Dear Mr Adeyemi,', 1], ['Hey!', 0], ['Yo,', 0], ['(no greeting)', 0]]],
    ['q' => 'You have three tasks due today and one due next week. Which should you do first?', 'd' => 'beginner', 'e' => 'Prioritise the items due soonest — the tasks due today.', 'o' => [['The tasks due today', 1], ['The task due next week', 0], ['The easiest task only', 0], ['None until reminded', 0]]],
    ['q' => 'In MS Word, which feature checks for misspelled words?', 'd' => 'beginner', 'e' => 'Spell Check flags misspelled words.', 'o' => [['Spell Check', 1], ['Mail Merge', 0], ['Track Changes', 0], ['Page Break', 0]]],
    ['q' => 'A visitor arrives for a meeting but the manager is running late. The best action is to:', 'd' => 'intermediate', 'e' => 'Acknowledge the visitor, offer a seat, and inform them of the short delay.', 'o' => [['Greet them, offer a seat and explain the brief delay', 1], ['Ignore them until the manager is free', 0], ['Tell them to come back another day', 0], ['Send them to the manager\'s office alone', 0]]],
    ['q' => 'Which tool is best for scheduling a recurring team meeting?', 'd' => 'beginner', 'e' => 'A calendar application handles recurring appointments.', 'o' => [['A calendar application', 1], ['A spreadsheet of names', 0], ['A printed poster', 0], ['A voice recorder', 0]]],
    ['q' => 'You receive a confidential document by mistake. You should:', 'd' => 'intermediate', 'e' => 'Do not share it; notify the sender and handle it confidentially.', 'o' => [['Inform the sender and keep it confidential', 1], ['Forward it to colleagues', 0], ['Post about it online', 0], ['Print extra copies', 0]]],
    ['q' => 'What is the purpose of "CC" in an email?', 'd' => 'intermediate', 'e' => 'CC (carbon copy) sends a visible copy to additional recipients for information.', 'o' => [['To send a visible copy to others for information', 1], ['To hide recipients', 0], ['To delete the email', 0], ['To encrypt the message', 0]]],
    ['q' => 'Two urgent requests arrive at once from different managers. The most professional response is to:', 'd' => 'advanced', 'e' => 'Communicate, clarify priorities/deadlines and agree an order rather than silently guessing.', 'o' => [['Ask both to confirm priority/deadlines and agree an order', 1], ['Do whichever is easiest and ignore the other', 0], ['Refuse both', 0], ['Wait until one complains', 0]]],
    ['q' => 'In a spreadsheet, freezing the top row is useful because it:', 'd' => 'intermediate', 'e' => 'Freezing keeps the header row visible while scrolling.', 'o' => [['Keeps the header visible while scrolling', 1], ['Deletes empty rows', 0], ['Locks the file from editing', 0], ['Prints the sheet', 0]]],
    ['q' => 'A good filing system should mainly be:', 'd' => 'advanced', 'e' => 'Consistent and logical naming makes records easy to retrieve.', 'o' => [['Consistent and easy to retrieve from', 1], ['Colourful above all', 0], ['Known only to you', 0], ['Stored in one giant folder', 0]]],
],

];
