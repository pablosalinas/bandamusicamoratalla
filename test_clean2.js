let fileName = '17 Saxo Barítono.pdf';
let matchedAliases = ['saxo baritono'];
let cleanedName = fileName
    .replace(/\.[^/.]+$/, '')
    .replace(/(?:^|\s|,|_|-)(?:1(?:st|o|a|er|º|ª)?|2(?:nd|o|a|do|º|ª)?|3(?:rd|o|a|er|ro|º|ª)?|4(?:th|o|a|to|º|ª)?|i|ii|iii|iv)(?:\s|,|_|-|$)/gi, ' ')
    .replace(/[-_]/g, ' ')
    .replace(/\b(sol|fa|do|re|mi|la|si|mib|sib|lab|bemol|sostenido)\b/gi, '')
    .replace(/\b(en|principal|pral|solo)\b/gi, '')
    .replace(/\d+/g, '')
    .replace(/\s+/g, ' ')
    .trim()
    .toLowerCase();

console.log('Cleaned before:', cleanedName);

for (let alias of matchedAliases) {
    let escapedAlias = alias.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    cleanedName = cleanedName.replace(new RegExp('\\b' + escapedAlias + '\\b', 'gi'), ' ');
}

console.log('Cleaned after:', cleanedName);