import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators, ReactiveFormsModule } from '@angular/forms';
import { CommonModule } from '@angular/common';
import { ItemService, Item } from './item.service';

@Component({
  selector: 'app-root',
  standalone: true,
  imports: [CommonModule, ReactiveFormsModule],
  templateUrl: './app.component.html'
})
export class AppComponent implements OnInit {
  itemForm!: FormGroup;
  items: Item[] = [];

  constructor(private fb: FormBuilder, private itemService: ItemService) {}

  ngOnInit(): void {
    this.itemForm = this.fb.group({
      name: ['', Validators.required],
      description: ['', Validators.required]
    });
    this.loadItems();
  }

  loadItems(): void {
    this.itemService.getItems().subscribe(data => this.items = data);
  }

  onSubmit(): void {
    if (this.itemForm.valid) {
      this.itemService.createItem(this.itemForm.value).subscribe(() => {
        this.itemForm.reset();
        this.loadItems();
      });
    }
  }

  onDelete(id: number): void {
    this.itemService.deleteItem(id).subscribe(() => this.loadItems());
  }
}
