<h2>School Response Count</h2>

<table border="0">
    <tr>
        <th>School</th>
        <th>Responses</th>
    </tr>
    
<?php foreach ($response_count as $school_data): ?>
    <tr>
        <td class="list_view">
            <?php echo $school_data['school_name']; ?>
        </td>
        <td class="list_view" style="text-align: right">
            <?php echo $school_data['response_count']; ?>
            
        </td>
    </tr>

<?php endforeach; ?>
</table>