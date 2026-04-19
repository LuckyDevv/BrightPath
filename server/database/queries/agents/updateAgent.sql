UPDATE `agents`
SET
    `name`=:name,
    `position`=:position,
    `birthdate`=:birthdate,
    `description`=:description,
    `biographic`=:biographic
WHERE
    `id`=:id;