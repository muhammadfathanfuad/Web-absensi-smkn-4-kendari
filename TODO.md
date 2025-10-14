# Plan to Handle Duplicate Data in Schedule Table

## Information Gathered
- JadwalController.php index method fetches timetables and maps to JSON array for display.
- Each timetable entry is shown as a separate row in the table.
- Duplicates occur when multiple consecutive time slots for the same subject, class, teacher, day, and type.
- Need to merge consecutive times into a single range (e.g., 08:00-08:40, 08:40-09:20, 09:20-10:00 -> 08:00-10:00).

## Plan
- Modify JadwalController.php index method to group timetables by day_of_week, class_subject_id, type.
- For each group, collect and sort time slots by start_time.
- Merge consecutive time slots where end_time of one equals start_time of next.
- Create a single entry with combined time range for each merged group.
- Ensure the output JSON structure remains compatible with the frontend Grid.js table.

## Dependent Files to Edit
- app/Http/Controllers/JadwalController.php

## Followup Steps
- [x] Test the modified index method to ensure correct merging.
- [x] Verify the table displays merged entries correctly.
- [x] Check for any edge cases (non-consecutive times, different types, etc.).
