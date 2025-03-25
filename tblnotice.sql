-- Table structure for notice board
CREATE TABLE IF NOT EXISTS tblnotice (
  id SERIAL PRIMARY KEY,
  noticeTitle varchar(255) NOT NULL,
  noticeDetails text,
  creationDate timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Sample notices for MSBTE diploma college
INSERT INTO tblnotice (noticeTitle, noticeDetails) VALUES
('Diploma Summer 2025 Exam Schedule Released', 'All students are hereby informed that the Summer 2025 examination schedule for all diploma courses has been released. Please check the MSBTE portal for detailed timetable. Students with backlogs must submit their exam forms before the deadline.'),
('MSBTE Online Verification Portal Open', 'The MSBTE online document verification portal is now open for diploma certificate verification. Students who need their documents verified for higher education or employment purposes can apply through the portal.'),
('Industrial Visit Schedule Updated', 'The schedule for industrial visits for the final year diploma students has been updated. Please check with your respective department coordinators for details about industry partners and visit dates.');