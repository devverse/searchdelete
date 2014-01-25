<!-- File: /app/View/Posts/index.ctp -->

<h1>Blog posts</h1>
<p><?php echo $this->Html->link('Add Post', array('action' => 'add')); ?></p>
<table>
    <tr>
        <th>Id</th>
        <th>Title</th>
        <th>Actions</th>
        <th>Created</th>
    </tr>

<!-- Here's where we loop through our $posts array, printing out post info -->

    <?php foreach ($insurances as $insurance): ?>
    <tr>
        <td><?php echo $insurance['Post']['id']; ?></td>
        <td>
            <?php
                echo $this->Html->link(
                    $insurance['Post']['title'],
                    array('action' => 'view', $insurance['Post']['id'])
                );
            ?>
        </td>
        <td>
            <?php
                echo $this->Form->postLink(
                    'Delete',
                    array('action' => 'delete', $insurance['Post']['id']),
                    array('confirm' => 'Are you sure?')
                );
            ?>
            <?php
                echo $this->Html->link(
                    'Edit', array('action' => 'edit', $insurance['Post']['id'])
                );
            ?>
        </td>
        <td>
            <?php echo $insurance['Post']['created']; ?>
        </td>
    </tr>
    <?php endforeach; ?>

</table>