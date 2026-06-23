-- Remove duplicate states keeping the lowest ID
DELETE s1 FROM states s1
INNER JOIN states s2
WHERE s1.id > s2.id
  AND s1.name = s2.name;

-- Add unique constraints
ALTER TABLE states ADD UNIQUE INDEX unique_state_name (name);
ALTER TABLE states ADD UNIQUE INDEX unique_state_slug (slug);