$(function () {
	var RemoveSection = $("[data-type=Remove]")
	var SectionContainer = $("[data-type=DuplicateSectionFieldsContainer]");
	var DuplicateSection = $("[data-type=Duplicate]");
	var AddSection = $("[data-type=AddSection]");
	var Index = $("[data-index=Index]");


	//For Question
	var RemoveSubSection = $("[data-type=SubRemove]")
	var SubSectionContainer = $("[data-type=DuplicateSubSectionFieldsContainer]");
	var DuplicateSubSection = $("[data-type=DuplicateSubSection]");
	var AddSubSection = $("[data-type=AddSubSection]");
	var ChildIndex = $("[data-index=ChildIndex]");
	Index = 0;
	ChildIndex = 0;
	//For Duplicate Section
	$('body').on('click', '[data-type=AddSection]', function () {
		var Div = Dev;
		if (typeof Div != 'undefined') {
			Div = Div.replace(/\{Index\}/g, Index);
			SectionContainer.append(Div);
			Index++;

		}
	});

	//For Duplicate Questions
	$('body').on('click', '[data-type=AddQuestion]', function () {
        var E = $(this);
        console.log(E);
        alert(E);
        var Parent = E.parents().first().parent();
        console.log(Parent)
		var ParentIndex = parseInt(E.attr('data-index'));
		var Template = Questions;
		Template = Template.I;
		if (typeof Template != 'undefined') {
			Template = Template.replace(/\{Index\}/g, Index);
			Template = Template.replace(/\{Index2\}/g, ParentIndex);
			Parent.append(Template);
			//Filled(ParentIndex);
			//console.log(Filled);
			Index++;
		}
	});

	//Remove Section
	$('body').on('click', '[data-type=Remove]', function (e) {
		e.preventDefault();
		$(this).parents('[data-type=Duplicate]').remove();
	});


	//Remove SubSections
	$('body').on('click', '[data-type=SubRemove]', function (e) {
		e.preventDefault();
		$(this).parents('[data-type=DuplicateSubSection]').first().remove();
	});
});
