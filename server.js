const express = require('express');
const fs = require('fs');
const path = require('path');
const app = express();
const port = 3000;

// Mock database function
const getDataFromDatabase = (option) => {
    const data = {
        'Option 1': 'This is the content for Option 1',
        'Option 2': 'This is the content for Option 2',
        'Option 3': 'This is the content for Option 3',
        'Option 4': 'This is the content for Option 4',
        'Option 5': 'This is the content for Option 5',
        'Option 6': 'This is the content for Option 6',
        'Option 7': 'This is the content for Option 7',
        'Option 8': 'This is the content for Option 8',
    };
    return data[option];
};

app.get('/download', (req, res) => {
    const option = req.query.option;
    const data = getDataFromDatabase(option);
    if (!data) {
        return res.status(404).send('Option not found');
    }

    const filePath = path.join(__dirname, `${option}.txt`);
    fs.writeFile(filePath, data, (err) => {
        if (err) {
            return res.status(500).send('Error writing file');
        }
        res.download(filePath, `${option}.txt`, (err) => {
            if (err) {
                console.error('Error during file download:', err);
            }
            fs.unlink(filePath, (err) => {
                if (err) {
                    console.error('Error deleting file:', err);
                }
            });
        });
    });
});

app.listen(port, () => {
    console.log(`Server is running on http://localhost:${port}`);
});
