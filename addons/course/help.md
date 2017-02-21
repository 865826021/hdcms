#开班模块

##获取开班列表

```
<tag action="course.lists" order="id desc" row="3">
    {{$field['name']}}
</tag>
```
所有字段都可以进行排序: desc降序 asc升序, row属性获取条数